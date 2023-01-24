<?php

namespace Tests\Unit\Model\Tariff;

use PHPUnit\Framework\TestCase;
use Tactics\FodAttest28186\Enum\FodSheetType;
use Tactics\FodAttest28186\Model\TaxSheet\TaxSheet;
use Tactics\FodAttest28186\Model\TaxSheet\TaxSheetMap;
use Tactics\FodAttest28186\Model\TaxSheet\TaxSheetUid;
use Tests\Unit\Factory\ChildFactory;
use Tests\Unit\Factory\DayOfBirthFactory;
use Tests\Unit\Factory\DebtorFactory;

final class TaxSheetMapTest extends TestCase
{
    /**
     * @var DebtorFactory
     */
    private $debtorFactory;
    /**
     * @var ChildFactory
     */
    private $childFactory;
    /**
     * @var DayOfBirthFactory
     */
    private $dayOfBirthFactory;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->debtorFactory = new DebtorFactory();
        $this->childFactory = new ChildFactory();
        $this->dayOfBirthFactory = new DayOfBirthFactory();
    }

    public function testTaxSheetLookup(): void
    {
        $map = TaxSheetMap::create();
        $type = FodSheetType::from(FodSheetType::NORMAL);

        $uuid1 = TaxSheetUid::fromString('38d43870-9db2');
        $dayOfBirth1 = $this->dayOfBirthFactory->create('1986-04-25');
        $child1 = $this->childFactory->create(false, $dayOfBirth1);
        $debtor1 = $this->debtorFactory->create(
            '65.03.06-006.36'
        );
        $sheet1 = TaxSheet::for($uuid1, $type, $debtor1, $child1);

        $uuid2 = TaxSheetUid::fromString('003b11ca-2401');
        $dayOfBirth2 = $this->dayOfBirthFactory->create('1990-09-02');
        $child2 = $this->childFactory->create(false, $dayOfBirth2);
        $debtor2 = $this->debtorFactory->create(
            '75.12.01-123.51'
        );
        $sheet2 = TaxSheet::for($uuid2, $type, $debtor2, $child2);

        foreach ([$sheet1, $sheet2] as $sheet) {
            $map = $map->add($sheet);
        }

        $this->assertTrue($map->lookUp($uuid2)->child()->equals($child2));
        $this->assertTrue($map->lookUp($uuid2)->debtor()->equals($debtor2));
    }

    public function testTaxSheetReplace(): void
    {
        $map = TaxSheetMap::create();
        $type = FodSheetType::from(FodSheetType::NORMAL);

        $uuid1 = TaxSheetUid::fromString('38d43870-9db2');
        $dayOfBirth1 = $this->dayOfBirthFactory->create('1986-04-25');
        $child1 = $this->childFactory->create(false, $dayOfBirth1);
        $debtor1 = $this->debtorFactory->create(
            '65.03.06-006.36'
        );
        $sheet1 = TaxSheet::for($uuid1, $type, $debtor1, $child1);

        $uuid2 = TaxSheetUid::fromString('003b11ca-2401');
        $dayOfBirth2 = $this->dayOfBirthFactory->create('1990-09-02');
        $child2 = $this->childFactory->create(false, $dayOfBirth2);
        $debtor2 = $this->debtorFactory->create(
            '75.12.01-123.51'
        );
        $sheet2 = TaxSheet::for($uuid2, $type, $debtor2, $child2);

        $map = $map->add($sheet1);
        $map = $map->replace($uuid1, $sheet2);

        $this->assertTrue($map->lookUp($uuid1)->child()->equals($child2));
        $this->assertTrue($map->lookUp($uuid1)->debtor()->equals($debtor2));
    }
}
