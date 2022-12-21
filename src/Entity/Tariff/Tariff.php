<?php

namespace Tactics\FodAttest28186\Entity\Tariff;

final class Tariff
{
    private int $days;

    private int $tariff;

    private TariffPeriod $period;

    /**
     * @param int $days
     * @param int $tariff
     * @param TariffPeriod $period
     */
    public function __construct(int $days, int $tariff, TariffPeriod $period)
    {
        $this->days = $days;
        $this->tariff = $tariff;
        $this->period = $period;
    }

    /**
     * @return int
     */
    public function days(): int
    {
        return $this->days;
    }

    /**
     * @return int
     */
    public function tariff(): int
    {
        return $this->tariff;
    }

    /**
     * @return TariffPeriod
     */
    public function period(): TariffPeriod
    {
        return $this->period;
    }
}
