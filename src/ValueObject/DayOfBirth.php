<?php

namespace Tactics\FodAttest28186\ValueObject;

use Carbon\Carbon;
use DateTimeImmutable;
use DateTimeInterface;
use TypeError;

final class DayOfBirth
{
    private Carbon $dayOfBirth;

    private function __construct(Carbon $dayOfBirth)
    {
        if ($dayOfBirth->isFuture()) {
            throw new TypeError('A date of birth can not be in the future');
        }
        $this->dayOfBirth = $dayOfBirth;
    }

    public static function fromDateTime(DateTimeInterface $dayOfBirth): DayOfBirth
    {
        $toCarbon = (new Carbon($dayOfBirth));
        return new self($toCarbon);
    }

    public function whenAge(int $age): DayOfBirth
    {
        $new = clone ($this);
        $new->dayOfBirth = $this->dayOfBirth->addYears($age);
        return $new;
    }

    public function isBefore(DateTimeInterface $dateTime): bool
    {
        $toCarbon = (new Carbon($dateTime))->startOfDay();
        return $this->dayOfBirth->startOfDay()->isBefore($toCarbon);
    }

    public function format(): string
    {
        return $this->dayOfBirth->format('d-m-Y');
    }
}
