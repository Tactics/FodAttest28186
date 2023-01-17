<?php

namespace Tactics\FodAttest28186\Model\TaxSheet;

use Generator;
use Tactics\FodAttest28186\Enum\FodSheetType;
use Tactics\FodAttest28186\Model\Child\Child;
use Tactics\FodAttest28186\Model\Debtor\Debtor;
use Tactics\FodAttest28186\Model\Tariff\Tariff;
use Tactics\FodAttest28186\Model\Tariff\TariffCollection;
use Tactics\FodAttest28186\Model\Tariff\TariffGrouping;
use TypeError;

final class TaxSheet
{
    private TaxSheetUid $uid;
    private Debtor $debtor;
    private Child $child;

    private array $tariffs;

    private FodSheetType $type;

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

    public function add(Tariff $tariff): TaxSheet
    {
        if (! $this->debtor->equals($tariff->debtor())) {
            throw new TypeError('invalid debtor');
        }

        if (! $this->child->equals($tariff->child())) {
            throw new TypeError('invalid child');
        }

        $new = clone ($this);
        $tariffs = [...$this->tariffs, $tariff];
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
