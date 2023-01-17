<?php

namespace Tests\Unit\Model\Tariff;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Tactics\FodAttest28186\Model\Tariff\Tariff;
use Tactics\FodAttest28186\Model\Tariff\TariffCollection;
use Tactics\FodAttest28186\Model\Tariff\TariffPeriod;
use Tactics\FodAttest28186\ValueObject\DayOfBirth;
use Tests\Unit\Factory\ChildFactory;
use Tests\Unit\Factory\DayOfBirthFactory;
use Tests\Unit\Factory\DebtorFactory;
use TypeError;

final class TariffCollectionTest extends TestCase
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

    public function testATariffCollectionCanOnlyContain4Items(): void
    {
        $this->expectException(TypeError::class);

        $start = DateTimeImmutable::createFromFormat('Y-m-d', '2021-01-01');
        $period = TariffPeriod::create($start, $start->modify('+2 months'));
        $debtor = $this->debtorFactory->create(
            '65.03.06-006.36'
        );
        $dayOfBirth = $this->dayOfBirthFactory->create('2021-04-25');
        $child = $this->childFactory->create(false, $dayOfBirth);

        $tariff = Tariff::create(
            10,
            100,
            $period,
            $debtor,
            $child
        );

        $collection = TariffCollection::create();
        foreach ([$tariff, $tariff, $tariff, $tariff, $tariff] as $item) {
            $collection = $collection->add($item);
        }
    }

    public function testATariffCollectionSumEqualsToSumOfAllTariffs(): void
    {
        $start = DateTimeImmutable::createFromFormat('Y-m-d', '2021-01-01');
        $period = TariffPeriod::create($start, $start->modify('+2 months'));
        $debtor = $this->debtorFactory->create(
            '65.03.06-006.36'
        );
        $dayOfBirth = $this->dayOfBirthFactory->create('2018-04-25');
        $child = $this->childFactory->create(false, $dayOfBirth);

        $tariff1 = Tariff::create(
            10,
            100,
            $period,
            $debtor,
            $child
        );

        $tariff2 = Tariff::create(
            10,
            300,
            $period,
            $debtor,
            $child
        );

        $collection = TariffCollection::create();
        foreach ([$tariff1, $tariff2] as $item) {
            $collection = $collection->add($item);
        }

        $this->assertEquals(4000, $collection->sum());
    }
}
