<?php

namespace Tests\Unit\Model\Tariff;

use Assert\AssertionFailedException;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Tactics\FodAttest28186\Model\Tariff\TariffPeriod;

final class TariffPeriodTest extends TestCase
{
    /**
     * @dataProvider periodeProvider
     * @testdox Test $testcase by testcase $start until $end.
     */
    public function testTariffPeriodValidation(
        string $start,
        string $end,
        string $testcase
    ): void {
        $this->expectException(AssertionFailedException::class);

        $startDateTime = DateTimeImmutable::createFromFormat('d-m-Y', $start);
        $endDateTime = DateTimeImmutable::createFromFormat('d-m-Y', $end);

        TariffPeriod::create(
            $startDateTime,
            $endDateTime
        );
    }

    public function periodeProvider(): iterable
    {
        yield [
            'start' => '12-03-2021',
            'end' => '01-02-2021',
            'testcase' => 'the end date of a periode can not be before the start date',
        ];
    }
}
