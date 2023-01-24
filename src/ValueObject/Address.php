<?php

namespace Tactics\FodAttest28186\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Tactics\FodAttest28186\Enum\FodCountryCode;

/**
 *
 */
final class Address
{
    /**
     * @var string
     */
    private $addressLine;

    /**
     * @var string
     */
    private $postal;

    /**
     * @var string
     */
    private $city;

    /**
     * @var FodCountryCode
     */
    private $countryCode;

    private function __construct(string $addressLine, string $postal, string $city, FodCountryCode $countryCode)
    {
        $this->addressLine = $addressLine;
        $this->postal = $postal;
        $this->city = $city;
        $this->countryCode = $countryCode;
    }

    /**
     * @throws AssertionFailedException
     */
    public static function forBelgium(string $addressLine, string $postal, string $city): Address
    {
        Assertion::numeric($postal, 'A Belgium postal must be numeric');
        Assertion::greaterOrEqualThan($postal, 1000, 'A valid Belgium postal cant be less than 1000');
        Assertion::lessOrEqualThan($postal, 9999, 'A valid Belgium postal cant be more than 9999');
        return new self($addressLine, $postal, $city, FodCountryCode::from(FodCountryCode::BELGIUM));
    }

    /**
     * @throws AssertionFailedException
     */
    public static function forForeign(string $addressLine, string $postal, string $city, FodCountryCode $countryCode): Address
    {
        Assertion::maxLength($postal, 12, 'Invalid postal code given of more than 12 characters');
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
