<?php

namespace Tactics\FodAttest28186\Model\TaxSheet;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Generator;
use Tactics\FodAttest28186\Enum\FodSheetType;
use Tactics\FodAttest28186\Model\Child\Child;
use Tactics\FodAttest28186\Model\Debtor\Debtor;
use Tactics\FodAttest28186\Model\Tariff\Tariff;
use Tactics\FodAttest28186\Model\Tariff\TariffCollection;
use Tactics\FodAttest28186\Model\Tariff\TariffGrouping;

final class TaxSheet
{
    /**
     * @var TaxSheetUid
     */
    private $uid;
    /**
     * @var Debtor
     */
    private $debtor;
    /**
     * @var Child
     */
    private $child;

    /**
     * @var array
     */
    private $tariffs;

    /**
     * @var FodSheetType
     */
    private $type;

    private function __construct(
        TaxSheetUid $uuid,
        FodSheetType $type,
        Debtor $debtor,
        Child $child
    ) {
        $this->uid = $uuid;
        $this->type = $type;
        $this->debtor = $debtor;
        $this->child = $child;
        $this->tariffs = [];
    }

    public static function for(
        TaxSheetUid $uuid,
        FodSheetType $type,
        Debtor $debtor,
        Child $child
    ): TaxSheet {
        return new self($uuid, $type, $debtor, $child);
    }

    /**
     * @throws AssertionFailedException
     */
    public function add(Tariff $tariff): TaxSheet
    {
        Assertion::true($this->debtor->equals($tariff->debtor()), 'invalid debtor');
        Assertion::true($this->child->equals($tariff->child()), 'invalid child');

        $new = clone ($this);
        $this->tariffs[] = $tariff;
        $tariffs = $this->tariffs;
        $new->tariffs = $tariffs;
        return $new;
    }

    public function of(Debtor $debtor, Child $child): bool
    {
        return $this->child->equals($child) && $this->debtor->equals($debtor);
    }

    public function uid(): TaxSheetUid
    {
        return $this->uid;
    }

    public function type(): FodSheetType
    {
        return $this->type;
    }

    /**
     * @return Generator<TariffCollection>
     * @throws AssertionFailedException
     */
    public function tariffGroups(): Generator
    {
        $collections = TariffGrouping::create();
        $chunks = array_chunk($this->tariffs, 4);

        foreach ($chunks as $chunk) {
            $collection = TariffCollection::create();
            foreach ($chunk as $tariff) {
                $collection = $collection->add($tariff);
            }
            $collections = $collections->add($collection);
        }

        yield from $collections;
    }

    public function debtor(): Debtor
    {
        return $this->debtor;
    }

    public function child(): Child
    {
        return $this->child;
    }
}
