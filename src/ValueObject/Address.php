<?php

namespace Tactics\FodAttest28186\ValueObject;

use InvalidArgumentException;
use Tactics\FodAttest28186\Enum\FodCountryCode;
use TypeError;

/**
 *
 */
final class Address
{
    private string $addressLine;

    private string $postal;

    private string $city;

    private FodCountryCode $countryCode;

    private function __construct(string $addressLine, string $postal, string $city, FodCountryCode $countryCode)
    {
        $this->addressLine = $addressLine;
        $this->postal = $postal;
        $this->city = $city;
        $this->countryCode = $countryCode;
    }

    public static function forBelgium(string $addressLine, string $postal, string $city): Address
    {
        if (!is_numeric($postal) || !(1000 <= (int)$postal && $postal <= 9999)) {
            throw new TypeError('Invalid postal code given');
        }

        return new self($addressLine, $postal, $city, FodCountryCode::from(FodCountryCode::BELGIUM));
    }

    public static function forForeign(string $addressLine, string $postal, string $city, FodCountryCode $countryCode): Address
    {
        if (strlen($postal) > 12) {
            throw new TypeError('Invalid postal code given');
        }

        return new self($addressLine, $postal, $city, $countryCode);
    }

    /**
     * @return string
     */
    public function addressLine(): string
    {
        return $this->addressLine;
    }

    /**
     * @return string
     */
    public function postal(): string
    {
        return $this->postal;
    }

    /**
     * @return string
     */
    public function city(): string
    {
        return $this->city;
    }

    /**
     * @return FodCountryCode
     */
    public function countryCode(): FodCountryCode
    {
        return $this->countryCode;
    }
}
