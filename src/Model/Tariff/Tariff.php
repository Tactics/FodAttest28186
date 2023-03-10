<?php

namespace Tactics\FodAttest28186\Model\Tariff;

use Assert\AssertionFailedException;
use DateTimeImmutable;
use RuntimeException;
use Tactics\FodAttest28186\Exception\InvalidEndDateException;
use Tactics\FodAttest28186\Exception\InvalidTariffException;
use Tactics\FodAttest28186\Model\Child\Child;
use Tactics\FodAttest28186\Model\Debtor\Debtor;

final class Tariff
{
    private int $days;
    private int $tariff;
    private TariffPeriod $period;
    private Debtor $debtor;
    private Child $child;

    /**
     * @param int $days
     * @param int $tariff
     * @param TariffPeriod $period
     * @param Debtor $debtor
     * @param Child $child
     *
     * @throws RuntimeException|InvalidTariffException|InvalidEndDateException
     */
    private function __construct(
        int $days,
        int $tariff,
        TariffPeriod $period,
        Debtor $debtor,
        Child $child
    ) {
        $maxAge = $child->isSeverelyDisabled() ? 21 : 14;
        $maxBirthday = $child->dayOfBirth()->whenAge($maxAge);

        if ($maxBirthday->isBeforeOrEqual($period->begin())) {
            throw new InvalidTariffException(
                sprintf(
                    'Tariff not allowed, child is %s at before start date of the tariff period %s',
                    $maxAge,
                    $period->begin()->format('Y-m-d')
                )
            );
        }

        if ($maxBirthday->isBeforeOrEqual($period->end())) {
            throw new InvalidEndDateException(sprintf(
                'Tariff end date %s is invalid, since the child turned %s before this date. The date should be corrected to %s (one day before he turns %s)',
                $period->end()->format('Y-m-d'),
                $maxAge,
                DateTimeImmutable::createFromFormat('d-m-Y', $maxBirthday->format())->modify('- 1 day')->format('Y-m-d'),
                $maxAge
            ));
        }

        $this->days = $days;
        $this->tariff = $tariff;
        $this->period = $period;
        $this->debtor = $debtor;
        $this->child = $child;
    }

    /**
     * @param int $days
     * @param int $tariff
     * @param TariffPeriod $period
     * @param Debtor $debtor
     * @param Child $child
     * @return Tariff
     * @throws InvalidEndDateException
     * @throws InvalidTariffException
     */
    public static function create(
        int $days,
        int $tariff,
        TariffPeriod $period,
        Debtor $debtor,
        Child $child
    ): Tariff {
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

    public function debtor(): Debtor
    {
        return $this->debtor;
    }

    public function child(): Child
    {
        return $this->child;
    }
}
