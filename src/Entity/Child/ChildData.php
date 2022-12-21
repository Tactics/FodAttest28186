<?php

namespace Tactics\FodAttest28186\Entity\Child;

use DateTimeImmutable;
use Tactics\FodAttest28186\ValueObject\Address;

/**
 * The child who has attended the care.
 *
 */
final class ChildData
{
    private string $name;

    private string $firstName;

    private Address $address;

    private DateTimeImmutable $birthDate;

    /**
     * @param string $name
     * @param string $firstName
     * @param Address $address
     * @param DateTimeImmutable $birthDate
     */
    public function __construct(string $name, string $firstName, Address $address, DateTimeImmutable $birthDate)
    {
        $this->name = $name;
        $this->firstName = $firstName;
        $this->address = $address;
        $this->birthDate = $birthDate;
    }


    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function firstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return Address
     */
    public function address(): Address
    {
        return $this->address;
    }

    /**
     * @return DateTimeImmutable
     */
    public function birthDate(): DateTimeImmutable
    {
        return $this->birthDate;
    }
}
