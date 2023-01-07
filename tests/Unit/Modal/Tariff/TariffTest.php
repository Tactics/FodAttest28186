<?php

namespace Unit\Modal\Tariff;

use DateTimeImmutable;
use Kinderopvang\Core\Validation\Child\ChildCodeValidator;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Tactics\FodAttest28186\Enum\FodCountryCode;
use Tactics\FodAttest28186\Modal\Child\ChildDetails;
use Tactics\FodAttest28186\Modal\Child\ChildWithNationalRegistry;
use Tactics\FodAttest28186\Modal\Child\ChildWithoutNationalRegistry;
use Tactics\FodAttest28186\Modal\Debtor\Debtor;
use Tactics\FodAttest28186\Modal\Tariff\Tariff;
use Tactics\FodAttest28186\Modal\Tariff\TariffPeriod;
use Tactics\FodAttest28186\ValueObject\Address;
use Tactics\FodAttest28186\ValueObject\DayOfBirth;
use Tactics\FodAttest28186\ValueObject\NationalRegistryNumber;
use TypeError;

final class TariffTest extends TestCase
{
    private function createDayOfBirth(string $date): DayOfBirth
    {
        $date = DateTimeImmutable::createFromFormat('Y-m-d', $date);
        return DayOfBirth::fromDateTime($date);
    }

    private function createAddress(): Address
    {
        return Address::forForeign(
            'Sesame Street 123',
            '10123',
            'Manhattan',
            FodCountryCode::from(FodCountryCode::USA)
        );
    }

    private function createDebtor(): Debtor
    {
        $rrn = NationalRegistryNumber::fromString('65.03.06-006.36');
        return Debtor::create(
            $rrn
        );
    }

    private function createChild(DayOfBirth $dayOfBirth): ChildWithoutNationalRegistry
    {
        return ChildWithoutNationalRegistry::create(
            'Doe',
            'Jane',
            $this->createAddress(),
            $dayOfBirth
        );
    }

    private function createSeverelyDisabledChild(DayOfBirth $dayOfBirth): ChildWithoutNationalRegistry
    {
        return ChildWithoutNationalRegistry::create(
            'Doe',
            'John',
            $this->createAddress(),
            $dayOfBirth
        )->withSevereDisability();
    }

    public function ageRulesProvider() : iterable
    {

        yield [
            'age' => '21',
            'disabled' => TRUE,
            'testcase' => 'a tariff can not be created for severely disabled children after the age of 21',
        ];

        yield [
            'age' => '14',
            'disabled' => FALSE,
            'testcase' => 'a tariff can not be created for children after the age of 14',
        ];
    }

    public function ageWarningProvider() : iterable
    {

        yield [
            'age' => '21',
            'disabled' => TRUE,
            'testcase' => 'a tariff created for a severely disabled child that ends on a day after the child turned 21, corrects the end date to 1 day before he/she turns 21 and adds a warning to the tariff',
        ];

        yield [
            'age' => '14',
            'disabled' => FALSE,
            'testcase' => 'a tariff created for a child that ends on a day after the child turned 14, corrects the end date to 1 day before he/she turns 14 and adds a warning to the tariff',
        ];
    }

    /**
     * @dataProvider ageRulesProvider
     * @testdox $testcase.
     */
    public function testAgeBasedBlockingValidation(
        string $age,
        bool $disabled,
        string $testcase
    ): void
    {
        $this->expectException(TypeError::class);

        $dayOfBirth = $this->createDayOfBirth('1986-04-25');
        $child = $disabled ?
            $this->createSeverelyDisabledChild($dayOfBirth) :
            $this->createChild($dayOfBirth);

        $birthdayOnAge = $dayOfBirth->whenAge($age);
        $dayAfterBirthdayOnAge = DateTimeImmutable::createFromFormat('d-m-Y', $birthdayOnAge->format())->modify('+1 day');

        $period = TariffPeriod::create($dayAfterBirthdayOnAge, $dayAfterBirthdayOnAge->modify('+1 month'));
        $debtor = $this->createDebtor();

        Tariff::create(
            10,
            100,
            $period,
            $debtor,
            $child
        );
    }

    /**
     * @dataProvider ageWarningProvider
     * @testdox $testcase.
     */
    public function testAgeBasedWarnings(
        string $age,
        bool $disabled,
        string $testcase
    ): void
    {
        $dayOfBirth = $this->createDayOfBirth('1986-04-25');
        $child = $disabled ?
            $this->createSeverelyDisabledChild($dayOfBirth) :
            $this->createChild($dayOfBirth);

        $birthdayOnAge = $dayOfBirth->whenAge($age);
        $monthBeforeBirthdayOnAge = DateTimeImmutable::createFromFormat('d-m-Y', $birthdayOnAge->format())->modify('-1 month');
        $monthAfterBirthdayOnAge = DateTimeImmutable::createFromFormat('d-m-Y', $birthdayOnAge->format())->modify('+1 month');

        $period = TariffPeriod::create($monthBeforeBirthdayOnAge, $monthAfterBirthdayOnAge);
        $debtor = $this->createDebtor();

        $tariff = Tariff::create(
            10,
            100,
            $period,
            $debtor,
            $child
        );

        $this->assertTrue($tariff->hasWarnings());
        $this->assertCount(1, $tariff->warnings());
        $this->assertEquals($birthdayOnAge->format(), $tariff->period()->end()->modify('+1 day')->format('d-m-Y'));
    }

}
