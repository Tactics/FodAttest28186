<?php

namespace Tactics\FodAttest28186\Model\Tariff;

use Generator;
use IteratorAggregate;

final class TariffGrouping implements IteratorAggregate
{
    /**
     * @var TariffCollection[]
     */
    private $grouping;

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
        $new = clone ($this);
        $this->grouping[] = $tariffCollection;
        $new->grouping = $this->grouping;
        return $new;
    }

    /**
     * @return Generator<TariffCollection>
     */
    public function getIterator(): Generator
    {
        foreach ($this->grouping as $group) {
            yield $group;
        }
    }
}
