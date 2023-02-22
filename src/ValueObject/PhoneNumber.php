<?php

namespace Tactics\FodAttest28186\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;

final class PhoneNumber
{
    private string $phoneNumber;

    /**
     * @throws AssertionFailedException
     */
    private function __construct(string $phoneNumber)
    {
        //replace all non-digit characters (except for the + sign) with an empty string
        $clean = preg_replace('/[^+0-9]/', '', $phoneNumber);
        Assertion::maxLength($clean, 12, 'Invalid argument passed for PhoneNumber');
        $this->phoneNumber = $clean;
    }

    /**
     * @throws AssertionFailedException
     */
    public static function fromString(string $phoneNumber): self
    {
        return new self($phoneNumber);
    }

    public function value(): string
    {
        return $this->phoneNumber;
    }

}
