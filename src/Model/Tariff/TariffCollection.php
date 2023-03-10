<?php

namespace Tactics\FodAttest28186\Model\Tariff;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Generator;

final class TariffCollection
{
    private array $values;

    public function __construct()
    {
        $this->values = [];
    }

    public static function create(): TariffCollection
    {
        return new self();
    }

    /**
     * @throws AssertionFailedException
     */
    public function add(Tariff $tariff): TariffCollection
    {
        Assertion::lessThan($this->length(), 4, 'Tariff collection can only consist of 4 items');
        $new = clone ($this);
        $values = [...$this->values, $tariff];
        $new->values = $values;

        return $new;
    }

    public function length(): int
    {
        return count($this->values);
    }

    /**
     * @return Generator<Tariff>
     */
    public function getIterator(): Generator
    {
        foreach ($this->values as $fiche) {
            yield $fiche;
        }
    }

    public function sum(): int
    {
        $sum = 0;
        /** @var Tariff $tariff */
        foreach ($this->values as $tariff) {
            $sum += $tariff->tariff() * $tariff->days();
        }
        return $sum;
    }

    public function getTariff($tariffNumber): ?Tariff
    {
        return $this->values[$tariffNumber - 1] ?? null;
    }
}
