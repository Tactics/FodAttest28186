<?php

namespace Tactics\FodAttest28186\Modal\TaxSheet;

use Generator;
use Tactics\FodAttest28186\Enum\FodSheetType;
use Tactics\FodAttest28186\Modal\Child\Child;
use Tactics\FodAttest28186\Modal\Debtor\Debtor;
use Tactics\FodAttest28186\Modal\Tariff\Tariff;
use Tactics\FodAttest28186\Modal\Tariff\TariffCollection;
use Tactics\FodAttest28186\Modal\Tariff\TariffGrouping;
use TypeError;

final class TaxSheet
{
    private TaxSheetUuid $uuid;
    private Debtor $debtor;
    private Child $child;

    private array $tariffs;

    private FodSheetType $type;

    private function __construct(
        TaxSheetUuid $uuid,
        FodSheetType $type,
        Debtor $debtor,
        Child $child
    ) {
        $this->uuid = $uuid;
        $this->type = $type;
        $this->debtor = $debtor;
        $this->child = $child;
        $this->tariffs = [];
    }

    public static function for(
        TaxSheetUuid $uuid,
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

    public function uuid(): TaxSheetUuid
    {
        return $this->uuid;
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
                $collection->add($tariff);
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
