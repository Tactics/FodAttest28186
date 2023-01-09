<?php

namespace Tests\Unit\ValueObject;

use DateTimeImmutable;
use Generator;
use PHPUnit\Framework\TestCase;
use Tactics\FodAttest28186\ValueObject\DayOfBirth;
use Tactics\FodAttest28186\ValueObject\NationalRegistryNumber;
use TypeError;

final class NationalRegistryNumberTest extends TestCase
{
    /**
     * @dataProvider dayOfBirthProvider
     * @testdox Test $testcase.
     */
    public function testDayOfBirthCalculation(
        DayOfBirth $birth,
        string $rnn,
        string $testcase,
        callable $test
    ): void {
        $nr = NationalRegistryNumber::fromString($rnn);
        $result = $nr->dayOfBirth()->isSameDay(DateTimeImmutable::createFromFormat('d-m-Y', $birth->format()));
        $test($result, $testcase);
    }

    /**
     * @dataProvider validNationalRegistryNumberProvider
     * @testdox Test $testcase.
     */
    public function testValidNationalRegistryNumber(
        string $rnn,
        string $testcase,
        callable $test
    ): void {
        $nr = NationalRegistryNumber::fromString($rnn);
        $test($nr->value());
    }

    public function testNationalRegistryNumberCanNotBeCreatedFromInvalidString(): void
    {
        $this->expectException(TypeError::class);
        NationalRegistryNumber::fromString('86.04.V1-125.86');
    }

    public function dayOfBirthProvider(): Generator
    {
        yield [
            'birth' => DayOfBirth::fromDateTime(DateTimeImmutable::createFromFormat('Y-m-d', '1986-04-25')),
            'rnn' => '86.04.25-125.86',
            'testcase' => 'day of birth can be calculated from national registry number',
            'test' => function ($result, $message) {
                self::assertTrue(
                    $result,
                    $message
                );
            },
        ];
    }

    public function validNationalRegistryNumberProvider(): Generator
    {
        yield [
            'rnn' => '86.04.25-125.86',
            'testcase' => 'national registry number can be created from formatted valid rrn string',
            'test' => function ($actual) {
                self::assertEquals(
                    '86042512586',
                    $actual
                );
            },
        ];

        yield [
            'rnn' => '90090230041',
            'testcase' => 'national registry number can be created from unformatted rrn string',
            'test' => function ($actual) {
                self::assertEquals(
                    90090230041,
                    $actual
                );
            },
        ];
    }
}
