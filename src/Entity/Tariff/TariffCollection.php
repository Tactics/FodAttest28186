<?php

namespace Tactics\FodAttest28186\Entity\Tariff;

final class TariffCollection
{
    private array $collection;

    public function __construct()
    {
        $this->collection = [];
    }

    public function add(Tariff $tariff): void
    {
        $this->collection[] = $tariff;
    }

    public function length(): int
    {
        return count($this->collection);
    }

    /**
     * @return Tariff[]
     */
    public function collection(): array
    {
        return $this->collection;
    }

    public function sum(): int
    {
        $sum = 0;
        /** @var Tariff $tariff */
        foreach ($this->collection as $tariff) {
            $sum += $tariff->tariff();
        }
        return $sum;
    }
}
