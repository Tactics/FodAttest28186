<?php

namespace Tactics\FodAttest28186\Model\Child;

use Tactics\FodAttest28186\ValueObject\Address;
use Tactics\FodAttest28186\ValueObject\DayOfBirth;

/**
 * The child who has attended the care.
 */
final class ChildDetails
{
    private string $familyName;

    private string $givenName;

    private Address $address;

    private DayOfBirth $dayOfBirth;

    /**
     * @param string $familyName
     * @param string $givenName
     * @param Address $address
     * @param DayOfBirth $dayOfBirth
     */
    public function __construct(
        string $familyName,
        string $givenName,
        Address $address,
        DayOfBirth $dayOfBirth
    ) {
        $this->familyName = trim($familyName);
        $this->givenName = trim($givenName);
        $this->address = $address;
        $this->dayOfBirth = $dayOfBirth;
    }

    public static function create(
        string $familyName,
        string $givenName,
        Address $address,
        DayOfBirth $dayOfBirth
    ): ChildDetails {
        return new self($familyName, $givenName, $address, $dayOfBirth);
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
}
