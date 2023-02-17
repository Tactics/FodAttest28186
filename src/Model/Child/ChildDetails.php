<?php

namespace Tactics\FodAttest28186\Model\Child;

use Tactics\FodAttest28186\Exception\EmptyFamilyNameException;
use Tactics\FodAttest28186\Exception\EmptyGivenNameException;
use Tactics\FodAttest28186\ValueObject\Address;
use Tactics\FodAttest28186\ValueObject\DayOfBirth;

/**
 * The child who has attended the care.
 */
final class ChildDetails
{
    /**
     * @var string
     */
    private $familyName;

    /**
     * @var string
     */
    private $givenName;

    /**
     * @var Address
     */
    private $address;

    /**
     * @var DayOfBirth
     */
    private $dayOfBirth;

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

    /**
     * @throws EmptyGivenNameException
     * @throws EmptyFamilyNameException
     */
    public static function create(
        string $familyName,
        string $givenName,
        Address $address,
        DayOfBirth $dayOfBirth
    ): ChildDetails {

        if (empty($givenName)) {
            throw new EmptyGivenNameException(
                'A child must have a given name'
            );
        }

        if (empty($familyName)) {
            throw new EmptyFamilyNameException(
                'A child must have a family name'
            );
        }

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
