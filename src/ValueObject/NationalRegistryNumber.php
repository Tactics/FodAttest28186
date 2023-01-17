<?php

namespace Tactics\FodAttest28186\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;
use DateTimeImmutable;
use SetBased\Rijksregisternummer\RijksregisternummerHelper;

final class NationalRegistryNumber
{
    private string $nationalRegistryNumber;

    /**
     * @param string $nationalRegistryNumber
     * @throws AssertionFailedException
     */
    private function __construct(string $nationalRegistryNumber)
    {
        $clean = RijksregisternummerHelper::clean($nationalRegistryNumber);
        Assertion::true(RijksregisternummerHelper::isValid($clean), 'Invalid argument passed for NationalRegistryNumber');
        $this->nationalRegistryNumber = $clean;
    }

    /**
     * @throws AssertionFailedException
     */
    public static function fromString(string $nationalRegistryNumber): NationalRegistryNumber
    {
        return new self($nationalRegistryNumber);
    }

    public function dayOfBirth(): DayOfBirth
    {
        return DayOfBirth::fromDateTime(
            DateTimeImmutable::createFromFormat(
                'Y-m-d',
                RijksregisternummerHelper::getBirthday($this->nationalRegistryNumber)
            )
        );
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->nationalRegistryNumber;
    }
}
