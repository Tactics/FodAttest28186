<?php

namespace Tactics\FodAttest28186\Model\Tariff;

use Assert\Assertion;
use Assert\AssertionFailedException;
use DateTimeImmutable;

final class TariffPeriod
{
    /**
     * @var DateTimeImmutable
     */
    private $begin;

    /**
     * @var DateTimeImmutable
     */
    private $end;

    /**
     * @param DateTimeImmutable $begin
     * @param DateTimeImmutable $end
     * @throws AssertionFailedException
     */
    private function __construct(DateTimeImmutable $begin, DateTimeImmutable $end)
    {
        Assertion::lessOrEqualThan($begin, $end, 'Begin can not be after end of tariff');
        Assertion::eq($begin->format('Y'), $end->format('Y'), 'Begin and end of tariff need to be in the same year');
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
