<?php

namespace Tactics\FodAttest28186\Model\Debtor;

use Tactics\FodAttest28186\Exception\EmptyFamilyNameException;
use Tactics\FodAttest28186\Exception\EmptyGivenNameException;
use Tactics\FodAttest28186\ValueObject\Address;
use Tactics\FodAttest28186\ValueObject\DayOfBirth;

/**
 * The person who has paid for the care
 */
final class DebtorDetails
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
     * @var string
     */
    private $placeOfBirth;

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
    ) {
        $this->familyName = trim($familyName);
        $this->givenName = trim($givenName);
        $this->address = $address;
        $this->dayOfBirth = $dayOfBirth;
        $this->placeOfBirth = trim($placeOfBirth);
    }

    /**
     * @throws EmptyGivenNameException
     * @throws EmptyFamilyNameException
     */
    public static function create(
        string $familyName,
        string $givenName,
        Address $address,
        DayOfBirth $dayOfBirth,
        string $placeOfBirth
    ): DebtorDetails {

        if (empty($givenName)) {
            throw new EmptyGivenNameException(
                'A debtor must have a given name'
            );
        }

        if (empty($familyName)) {
            throw new EmptyFamilyNameException(
                'A debtor must have a family name'
            );
        }

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
