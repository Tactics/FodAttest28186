<?php

namespace Tactics\FodAttest28186\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;

final class CompanyNumber
{
    private const VALID_FIRST_CHAR = [0, 1];

    private string $companyNumber;

    /**
     * @param string $companyNumber
     * @throws AssertionFailedException
     */
    private function __construct(string $companyNumber)
    {
        // Get first character from $number
        $firstChar = $companyNumber[0];

        //Check if the Company number starts with either 0 or 1.
        Assertion::inArray((int) $firstChar, self::VALID_FIRST_CHAR, 'Invalid company number passed: Must start with 0 or 1');

        $clean = str_replace('.', '', $companyNumber);
        Assertion::numeric($clean, 'Invalid company number passed: Must contain only digits');
        Assertion::length($clean, 10, 'Invalid company number passed: Must contain 10 digitss');

        $this->companyNumber = $clean;
    }

    /**
     * @throws AssertionFailedException
     */
    public static function fromString(string $companyNumber): CompanyNumber
    {
        return new self($companyNumber);
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->companyNumber;
    }
}
