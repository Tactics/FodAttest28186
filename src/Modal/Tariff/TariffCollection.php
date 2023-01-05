<?php

namespace Tactics\FodAttest28186\Modal\Tariff;

use TypeError;

final class TariffCollection
{
    private array $values;

    public function __construct() {
        $this->values = [];
    }

    public static function create(): TariffCollection
    {
        return new self();
    }

    public function add(Tariff $tariff): TariffCollection
    {
        if ($this->length() >= 4) {
            throw new TypeError('Tariff collection can only consist of 4 items');
        }

        $new = clone($this);
        $values = [...$this->values, $tariff];
        $new->values = $values;

        return $new;
    }

    public function length(): int
    {
        return count($this->values);
    }

    /**
     * @return Tariff[]
     */
    public function collection(): array
    {
        return $this->values;
    }

    public function sum(): int
    {
        $sum = 0;
        /** @var Tariff $tariff */
        foreach ($this->values as $tariff) {
            $sum += $tariff->tariff();
        }
        return $sum;
    }

}