<?php

namespace Tactics\FodAttest28186\Model\Tariff;

use Assert\Assertion;
use Assert\AssertionFailedException;
use DateTimeImmutable;

final class TariffPeriod
{
    private DateTimeImmutable $begin;

    private DateTimeImmutable $end;

    /**
     * @param DateTimeImmutable $begin
     * @param DateTimeImmutable $end
     * @throws AssertionFailedException
     */
    private function __construct(DateTimeImmutable $begin, DateTimeImmutable $end)
    {
        Assertion::lessOrEqualThan($begin, $end, 'Begin can not be after end of tariff');
        $this->begin = $begin;
        $this->end = $end;
    }

    /**
     * @throws AssertionFailedException
     */
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
