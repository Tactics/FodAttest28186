<?php

namespace Tests\Unit\Model\Tariff;

use Assert\AssertionFailedException;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Tactics\FodAttest28186\Model\Tariff\Tariff;
use Tactics\FodAttest28186\Model\Tariff\TariffPeriod;
use Tests\Unit\Factory\ChildFactory;
use Tests\Unit\Factory\DayOfBirthFactory;
use Tests\Unit\Factory\DebtorFactory;

final class TariffTest extends TestCase
{
    private DebtorFactory $debtorFactory;
    private ChildFactory $childFactory;
    private DayOfBirthFactory $dayOfBirthFactory;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
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

        yield [
            'age' => '21',
            'disabled' => true,
            'testcase' => 'a tariff created for a severely disabled child that ends on a day after the child turned 21 is invalid, and suggests to corrects the end date to 1 day before he/she turns 21',
        ];

        yield [
            'age' => '14',
            'disabled' => false,
            'testcase' => 'a tariff created for a child that ends on a day after the child turned 14 is invalid, and suggests to corrects the end date to 1 day before he/she turns 14',
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
        $this->expectException(AssertionFailedException::class);

        $dayOfBirth = $this->dayOfBirthFactory->create('1986-04-25');
        $child = $this->childFactory->create($disabled, $dayOfBirth);

        $birthdayOnAge = $dayOfBirth->whenAge($age);
        $dayAfterBirthdayOnAge = DateTimeImmutable::createFromFormat('d-m-Y', $birthdayOnAge->format())->modify('+1 day');

        $period = TariffPeriod::create($dayAfterBirthdayOnAge, $dayAfterBirthdayOnAge->modify('+1 month'));
        $debtor = $this->debtorFactory->create(
            '65.03.06-006.36'
        );

        Tariff::create(
            10,
            100,
            $period,
            $debtor,
            $child
        );
    }
}
