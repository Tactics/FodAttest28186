<?php

namespace Tactics\FodAttest28186\Modal\Tariff;

use DateTimeImmutable;
use InvalidArgumentException;

final class TariffPeriod
{
    private DateTimeImmutable $begin;

    private DateTimeImmutable $end;

    /**
     * @param DateTimeImmutable $begin
     * @param DateTimeImmutable $end
     */
    private function __construct(DateTimeImmutable $begin, DateTimeImmutable $end)
    {
        if ($begin > $end) {
            throw new InvalidArgumentException('Begin can not be before end of tariff');
        }

        $this->begin = $begin;
        $this->end = $end;
    }

    public static function create(DateTimeImmutable $begin, DateTimeImmutable $end): TariffPeriod
    {
        return new self($begin, $end);
    }

    /**
     * @return DateTimeImmutable
     */
    public function begin(): DateTimeImmutable
    {
        return $this->begin;
    }

    /**
     * @return DateTimeImmutable
     */
    public function end(): DateTimeImmutable
    {
        return $this->end;
    }
}