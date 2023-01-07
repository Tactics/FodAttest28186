<?php

namespace Tactics\FodAttest28186\ValueObject;

use DateTimeImmutable;
use InvalidArgumentException;
use SetBased\Rijksregisternummer\RijksregisternummerHelper;

final class NationalRegistryNumber
{
    private string $nationalRegistryNumber;

    /**
     * @param string $nationalRegistryNumber
     */
    private function __construct(string $nationalRegistryNumber)
    {
        $clean = RijksregisternummerHelper::clean($nationalRegistryNumber);
        if (!RijksregisternummerHelper::isValid($clean)) {
            throw new InvalidArgumentException('Invalid argument passed for NationalRegistryNumber');
        }

        $this->nationalRegistryNumber = $clean;
    }

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
