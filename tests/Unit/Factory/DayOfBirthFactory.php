<?php

namespace Tests\Unit\Factory;

use DateTimeImmutable;
use Tactics\FodAttest28186\ValueObject\DayOfBirth;

final class DayOfBirthFactory
{
    public function __construct() {}

    public function create(string $date): DayOfBirth
    {
        $date = DateTimeImmutable::createFromFormat('Y-m-d', $date);
        return DayOfBirth::fromDateTime($date);
    }
}

