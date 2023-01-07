<?php

namespace Tests\Unit\Modal\Tariff;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Tactics\FodAttest28186\Modal\Tariff\Tariff;
use Tactics\FodAttest28186\Modal\Tariff\TariffCollection;
use Tactics\FodAttest28186\Modal\Tariff\TariffPeriod;
use Tactics\FodAttest28186\ValueObject\DayOfBirth;
use Tests\Unit\Factory\ChildFactory;
use Tests\Unit\Factory\DayOfBirthFactory;
use Tests\Unit\Factory\DebtorFactory;
use TypeError;

final class TariffCollectionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->debtorFactory = new DebtorFactory();
        $this->childFactory = new ChildFactory();
        $this->dayOfBirthFactory = new DayOfBirthFactory();
    }

    public function testATariffCollectionCanOnlyContain4Items(): void
    {
        $this->expectException(TypeError::class);

        $start = DateTimeImmutable::createFromFormat('Y-m-d', '2021-01-01');
        $period = TariffPeriod::create($start, $start->modify('+2 months'));
        $debtor = $this->debtorFactory->create();
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
        $debtor = $this->debtorFactory->create();
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

        $this->assertEquals(400, $collection->sum());
    }
}
