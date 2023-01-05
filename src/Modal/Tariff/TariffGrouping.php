<?php

namespace Tactics\FodAttest28186\Modal\Tariff;

use Generator;
use IteratorAggregate;

final class TariffGrouping implements IteratorAggregate
{
    /**
     * @var TariffCollection[]
     */
    private array $grouping;

    private function __construct()
    {
        $this->grouping = [];
    }

    public static function create(): TariffGrouping
    {
        return new self();
    }

    public function add(TariffCollection $tariffCollection): TariffGrouping
    {
        $new = clone($this);
        $grouping = [...$this->grouping, $tariffCollection];
        $new->grouping = $grouping;
        return $new;
    }

    /**
     * @return Generator<TariffCollection>
     */
    public function getIterator() : Generator
    {
        foreach ($this->grouping as $group) {
            yield $group;
        }
    }
}
