<?php

namespace Tactics\FodAttest28186\ValueObject;

use InvalidArgumentException;
use TypeError;

final class CompanyNumber
{
    private const VALID_FIRST_CHAR = [0, 1];

    private string $companyNumber;

    /**
     * @param string $companyNumber
     */
    private function __construct(string $companyNumber)
    {
        // Get first character from $number
        $firstChar = $companyNumber[0];

        //Check if the Company number starts with either 0 or 1.
        if (!in_array((int) $firstChar, self::VALID_FIRST_CHAR, true)) {
            throw new TypeError('Invalid company number passed: Must start with 0 or 1');
        }

        $clean = str_replace('.', '', $companyNumber);
        if (!is_numeric($clean)) {
            throw new TypeError('Invalid company number passed: Must contain only digits');
        }

        if (strlen($clean) !== 10) {
            throw new TypeError('Invalid company number passed: Must contain 10 digits');
        }

        $this->companyNumber = $clean;
    }

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
