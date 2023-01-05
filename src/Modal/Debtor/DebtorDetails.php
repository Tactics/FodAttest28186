<?php

namespace Tactics\FodAttest28186\Modal\Debtor;

use DateTimeImmutable;
use Tactics\FodAttest28186\ValueObject\Address;
use Tactics\FodAttest28186\ValueObject\DayOfBirth;

/**
 * The person who has paid for the care
 */
final class DebtorDetails
{
    private string $familyName;

    private string $givenName;

    private Address $address;

    private DayOfBirth $dayOfBirth;

    private string $placeOfBirth;

    /**
     * @param string $familyName
     * @param string $givenName
     * @param Address $address
     * @param DayOfBirth $dayOfBirth
     * @param string $placeOfBirth
     */
    private function __construct(
        string $familyName,
        string $givenName,
        Address $address,
        DayOfBirth $dayOfBirth,
        string $placeOfBirth
    )
    {
        $this->familyName = $familyName;
        $this->givenName = $givenName;
        $this->address = $address;
        $this->dayOfBirth = $dayOfBirth;
        $this->placeOfBirth = $placeOfBirth;
    }

    public static function create(
        string $familyName,
        string $givenName,
        Address $address,
        DayOfBirth $dayOfBirth,
        string $placeOfBirth
    ) : DebtorDetails {
        return new self(
            $familyName,
            $givenName,
            $address,
            $dayOfBirth,
            $placeOfBirth
        );
    }

    /**
     * @return string
     */
    public function familyName(): string
    {
        return $this->familyName;
    }

    /**
     * @return string
     */
    public function givenName(): string
    {
        return $this->givenName;
    }

    /**
     * @return Address
     */
    public function address(): Address
    {
        return $this->address;
    }

    /**
     * @return DayOfBirth
     */
    public function dayOfBirth(): DayOfBirth
    {
        return $this->dayOfBirth;
    }

    public function placeOfBirth(): string
    {
        return $this->placeOfBirth;
    }
}
