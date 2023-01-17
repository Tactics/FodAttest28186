<?php

namespace Tests\Unit\ValueObject;

use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;
use Tactics\FodAttest28186\ValueObject\CompanyNumber;

final class CompanyNumberTest extends TestCase
{
    public function testACompanyNumberMustStartWithOneOrZero(): void
    {
        $companyNr1 = CompanyNumber::fromString('012.3456.743');
        $companyNr2 = CompanyNumber::fromString('112.3556.523');

        $this->assertEquals('0123456743', $companyNr1->value());
        $this->assertEquals('1123556523', $companyNr2->value());
    }

    public function testACompanyNumberMustContainTenNumbers(): void
    {
        $this->expectException(AssertionFailedException::class);
        CompanyNumber::fromString('012.3456.7431');
    }

    public function testACompanyNumberCanContainOnlyNumbersAndDots(): void
    {
        $this->expectException(AssertionFailedException::class);
        CompanyNumber::fromString('012-3456-7431');
    }

    public function testACompanyNumberCanNotContainLetters(): void
    {
        $this->expectException(AssertionFailedException::class);
        CompanyNumber::fromString('012.A123.123');
    }
}
