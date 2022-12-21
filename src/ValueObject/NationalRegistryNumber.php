<?php

namespace Tactics\FodAttest28186\ValueObject;

use SetBased\Rijksregisternummer\RijksregisternummerHelper;

final class NationalRegistryNumber
{
    private string $nationalRegistryNumber;

    /**
     * @param string $nationalRegistryNumber
     */
    public function __construct(string $nationalRegistryNumber)
    {
        $clean = RijksregisternummerHelper::clean($nationalRegistryNumber);
        if (!RijksregisternummerHelper::isValid($clean)) {
            throw new \InvalidArgumentException('Invalid argument passed for NationalRegistryNumber');
        }

        $this->nationalRegistryNumber = $clean;
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->nationalRegistryNumber;
    }
}
