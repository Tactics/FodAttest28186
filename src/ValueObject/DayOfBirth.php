<?php

namespace Tactics\FodAttest28186\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Carbon\Carbon;
use DateTimeInterface;

final class DayOfBirth
{
    private Carbon $dayOfBirth;

    /**
     * @throws AssertionFailedException
     */
    private function __construct(Carbon $dayOfBirth)
    {
        Assertion::false($dayOfBirth->isFuture(), 'A date of birth can not be in the future');
        $this->dayOfBirth = $dayOfBirth;
    }

    /**
     * @throws AssertionFailedException
     */
    public static function fromDateTime(DateTimeInterface $dayOfBirth): DayOfBirth
    {
        $toCarbon = (new Carbon($dayOfBirth));
        return new self($toCarbon);
    }

    public function whenAge(int $age): DayOfBirth
    {
        $new = clone ($this);
        $dayOfBirth = clone ($this->dayOfBirth);
        $new->dayOfBirth = $dayOfBirth->addYears($age);
        return $new;
    }

    public function isBefore(DateTimeInterface $dateTime): bool
    {
        $toCarbon = (new Carbon($dateTime))->startOfDay();
        return $this->dayOfBirth->startOfDay()->isBefore($toCarbon);
    }

    public function isSameDay(DateTimeInterface $dateTime): bool
    {
        $toCarbon = (new Carbon($dateTime))->startOfDay();
        return $this->dayOfBirth->startOfDay()->isSameDay($toCarbon);
    }

    public function isBeforeOrEqual(DateTimeInterface $dateTime): bool
    {
        $toCarbon = (new Carbon($dateTime))->startOfDay();
        return $this->dayOfBirth->startOfDay()->isBefore($toCarbon) ||
            $this->dayOfBirth->startOfDay()->isSameDay($toCarbon);
    }

    public function format(): string
    {
        return $this->dayOfBirth->format('d-m-Y');
    }

    /**
     * @return \DateTime
     */
    public function toPhpDateTime(): \DateTime
    {
        return $this->dayOfBirth->toDateTime();
    }
}
