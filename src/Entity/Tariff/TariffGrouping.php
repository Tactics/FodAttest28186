<?php

namespace Tactics\FodAttest28186\Entity\Tariff;

final class TariffGrouping
{
    /**
     * @var TariffCollection[]
     */
    private array $grouping;

    public function __construct()
    {
        $tariffCollection = new TariffCollection();
        $this->grouping = [$tariffCollection];
    }

    public function add(Tariff $tariff): void
    {
        $lastTariffCollection = end($this->grouping);
        if ($lastTariffCollection->length() < 4) {
            $lastTariffCollection->add($tariff);
        } else {
            $tariffCollection = new TariffCollection();
            $tariffCollection->add($tariff);
            $this->grouping[] = $tariffCollection;
        }
    }

    /**
     * @return TariffCollection[]
     */
    public function grouping(): array
    {
        return $this->grouping;
    }
}
