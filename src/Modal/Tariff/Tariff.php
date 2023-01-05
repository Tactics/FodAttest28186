<?php

namespace Tactics\FodAttest28186\Modal\Tariff;

use RuntimeException;
use Tactics\FodAttest28186\Modal\Child\Child;
use Tactics\FodAttest28186\Modal\Debtor\Debtor;

final class Tariff
{
    private int $days;
    private int $tariff;
    private TariffPeriod $period;
    private Debtor $debtor;
    private Child $child;

    private array $warnings = [];

    /**
     * @param int $days
     * @param int $tariff
     * @param TariffPeriod $period
     * @param Debtor $debtor
     * @param Child $child
     *
     * @throws RuntimeException
     */
    private function __construct(
        int $days,
        int $tariff,
        TariffPeriod $period,
        Debtor $debtor,
        Child $child
    )
    {
        $maxAge = $child->isSeverelyDisabled() ? 21 : 14;
        $maxBirthday = $child->dayOfBirth()->whenAge($maxAge);
        if ($maxBirthday->isBefore($period->begin())) {
            throw new RuntimeException(
                sprintf(
                    'Tariff not allowed, child is %s at before start date of the tariff period %s',
                    $maxAge,
                    $period->begin()->format('Y-m-d')
                )
            );
        }

        if ($maxBirthday->isBefore($period->end())) {
            $this->warnings[] = sprintf(
                'Tariff end date %s is invalid, since the child turned %s before this date.',
                $period->end()->format('Y-m-d'),
                $maxAge
            );

            $begin = $period->begin();
            $period = TariffPeriod::create($begin, $maxBirthday->toDateTime()->modify('- 1 day'));
        }

        $this->days = $days;
        $this->tariff = $tariff;
        $this->period = $period;
        $this->debtor = $debtor;
        $this->child = $child;
    }

    public static function create(
        int $days,
        int $tariff,
        TariffPeriod $period,
        Debtor $debtor,
        Child $child
    ): Tariff
    {
        return new self($days, $tariff, $period, $debtor, $child);
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

    public function debtor() : Debtor {
        return $this->debtor;
    }

    public function child() : Child {
        return $this->child;
    }

    public function hasWarnings(): bool {
        return count($this->warnings) > 0;
    }

    public function warnings(): array {
        return $this->warnings;
    }
}