<?php

namespace Tests\Unit\Modal\Tariff;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Tactics\FodAttest28186\Modal\Tariff\Tariff;
use Tactics\FodAttest28186\Modal\Tariff\TariffPeriod;
use Tests\Unit\Factory\ChildFactory;
use Tests\Unit\Factory\DayOfBirthFactory;
use Tests\Unit\Factory\DebtorFactory;
use TypeError;

final class TariffTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->debtorFactory = new DebtorFactory();
        $this->childFactory = new ChildFactory();
        $this->dayOfBirthFactory = new DayOfBirthFactory();
    }

    public function ageRulesProvider(): iterable
    {
        yield [
            'age' => '21',
            'disabled' => true,
            'testcase' => 'a tariff can not be created for severely disabled children after the age of 21',
        ];

        yield [
            'age' => '14',
            'disabled' => false,
            'testcase' => 'a tariff can not be created for children after the age of 14',
        ];
    }

    public function ageWarningProvider(): iterable
    {
        yield [
            'age' => '21',
            'disabled' => true,
            'testcase' => 'a tariff created for a severely disabled child that ends on a day after the child turned 21, corrects the end date to 1 day before he/she turns 21 and adds a warning to the tariff',
        ];

        yield [
            'age' => '14',
            'disabled' => false,
            'testcase' => 'a tariff created for a child that ends on a day after the child turned 14, corrects the end date to 1 day before he/she turns 14 and adds a warning to the tariff',
        ];
    }

    /**
     * @dataProvider ageRulesProvider
     * @testdox Test $testcase.
     */
    public function testAgeBasedBlockingValidation(
        string $age,
        bool $disabled,
        string $testcase
    ): void {
        $this->expectException(TypeError::class);

        $dayOfBirth = $this->dayOfBirthFactory->create('1986-04-25');
        $child = $this->childFactory->create($disabled, $dayOfBirth);

        $birthdayOnAge = $dayOfBirth->whenAge($age);
        $dayAfterBirthdayOnAge = DateTimeImmutable::createFromFormat('d-m-Y', $birthdayOnAge->format())->modify('+1 day');

        $period = TariffPeriod::create($dayAfterBirthdayOnAge, $dayAfterBirthdayOnAge->modify('+1 month'));
        $debtor = $this->debtorFactory->create(        '65.03.06-006.36'
        );

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
     * @testdox Test $testcase.
     */
    public function testAgeBasedWarnings(
        string $age,
        bool $disabled,
        string $testcase
    ): void {
        $dayOfBirth = $this->dayOfBirthFactory->create('1986-04-25');
        $child = $this->childFactory->create($disabled, $dayOfBirth);

        $birthdayOnAge = $dayOfBirth->whenAge($age);
        $monthBeforeBirthdayOnAge = DateTimeImmutable::createFromFormat('d-m-Y', $birthdayOnAge->format())->modify('-1 month');
        $monthAfterBirthdayOnAge = DateTimeImmutable::createFromFormat('d-m-Y', $birthdayOnAge->format())->modify('+1 month');

        $tariff = Tariff::create(
            10,
            100,
            TariffPeriod::create($monthBeforeBirthdayOnAge, $monthAfterBirthdayOnAge),
            $this->debtorFactory->create(        '65.03.06-006.36'
            ),
            $child
        );

        $this->assertTrue($tariff->hasWarnings());
        $this->assertCount(1, $tariff->warnings());
        $this->assertEquals($birthdayOnAge->format(), $tariff->period()->end()->modify('+1 day')->format('d-m-Y'));
    }
}
