<?php

namespace Tactics\FodAttest28186\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;

final class DivisionNumber
{
    private $value;

    /**
     * @param int $value
     * @throws AssertionFailedException
     */
    private function __construct(int $value)
    {
        Assertion::greaterThan($value, 0, 'Invalid division number passed: Must contain contain a value between 0 and 10000');
        Assertion::lessThan($value, 10000, 'Invalid division number passed: Must contain contain a value between 1 and 10000');

        $this->value = $value;
    }

    /**
     * @throws AssertionFailedException
     */
    public static function from(int $value): DivisionNumber
    {
        return new self($value);
    }

    /**
     * @return int
     */
    public function value(): int
    {
        return $this->value;
    }
}
