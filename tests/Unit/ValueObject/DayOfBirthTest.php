<?php

namespace Tests\Unit\ValueObject;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Tactics\FodAttest28186\ValueObject\DayOfBirth;
use TypeError;

final class DayOfBirthTest extends TestCase
{
    public function testADayOfBirthIsAlwaysInThePast(): void
    {
        $this->expectException(TypeError::class);

        $future = (new DateTimeImmutable())->modify('+1 day');
        DayOfBirth::fromDateTime($future);
    }

    public function testAValidDayOfBirthGetsCreated(): void
    {
        $past = (new DateTimeImmutable())->modify('-1 day');
        $dayOfBirth = DayOfBirth::fromDateTime($past);

        $this->assertEquals($past->format('d-m-Y'), $dayOfBirth->format());
    }

    public function testThatABirthdayCanBeDerivedFromADayOfBirth(): void
    {
        $rawDayOfBirth = DateTimeImmutable::createFromFormat('Y-m-d', '1986-04-25');
        $rawSweetSixteen = DateTimeImmutable::createFromFormat('Y-m-d', '2002-04-25');
        $dayOfBirth = DayOfBirth::fromDateTime($rawDayOfBirth);
        $sweetSixteen = $dayOfBirth->whenAge(16);

        $this->assertEquals($rawSweetSixteen->format('d-m-Y'), $sweetSixteen->format());
    }

    public function testADayOfBirthDisplaysInTheCorrectFormat(): void
    {
        $rawDayOfBirth = DateTimeImmutable::createFromFormat('Y-m-d', '1986-04-25');
        $dayOfBirth = DayOfBirth::fromDateTime($rawDayOfBirth);

        $this->assertEquals('25-04-1986', $dayOfBirth->format());
    }

    public function testADayOfBirthKnowsWhetherItIsBeforeASpecificMomentInTime(): void
    {
        $rawDate1 = DateTimeImmutable::createFromFormat('Y-m-d', '1986-04-25');
        $rawDate2 = DateTimeImmutable::createFromFormat('Y-m-d', '2002-04-25');
        $rawDate3 = DateTimeImmutable::createFromFormat('Y-m-d', '1986-03-25');

        $date1 = DayOfBirth::fromDateTime($rawDate1);
        $date2 = DayOfBirth::fromDateTime($rawDate2);
        $date3 = DayOfBirth::fromDateTime($rawDate3);

        $this->assertEquals(true, $date1->isBefore($rawDate2));
        $this->assertEquals(false, $date1->isBefore($rawDate3));
        $this->assertEquals(false, $date2->isBefore($rawDate1));
        $this->assertEquals(false, $date2->isBefore($rawDate3));
        $this->assertEquals(true, $date3->isBefore($rawDate1));
        $this->assertEquals(true, $date3->isBefore($rawDate2));
    }
}
