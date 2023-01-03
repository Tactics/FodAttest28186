<?php

namespace Tactics\FodAttest28186\Entity\Debtor;

use DateTimeImmutable;
use Tactics\FodAttest28186\Entity\Child\Child;
use Tactics\FodAttest28186\Entity\Tariff\TariffGrouping;
use Tactics\FodAttest28186\ValueObject\Address;
use Tactics\FodAttest28186\ValueObject\CompanyNumber;
use Tactics\FodAttest28186\ValueObject\NationalRegistryNumber;

/**
 * The person who has paid for the care
 */
final class Debtor
{
    private string $name;

    private string $firstName;

    private Address $address;

    private DateTimeImmutable $birthDate;

    private string $birthPlace;

    private Child $child;

    private ?CompanyNumber $companyNumber = null;

    private ?NationalRegistryNumber $nationalRegistryNumber = null;

    /**
     * @param string $name
     * @param string $firstName
     * @param Address $address
     * @param DateTimeImmutable $birthDate
     * @param string $birthPlace
     * @param Child $child
     */
    public function __construct(string $name, string $firstName, Address $address, DateTimeImmutable $birthDate, string $birthPlace, Child $child)
    {
        $this->name = $name;
        $this->firstName = $firstName;
        $this->address = $address;
        $this->birthDate = $birthDate;
        $this->birthPlace = $birthPlace;
        $this->child = $child;
    }

    public function withCompanyNumber(CompanyNumber $companyNumber): Debtor
    {
        $clone = clone $this;
        $clone->companyNumber = $companyNumber;

        return $clone;
    }

    public function withNationalRegistryNumber(NationalRegistryNumber $nationalRegistryNumber): Debtor
    {
        $clone = clone $this;
        $clone->nationalRegistryNumber = $nationalRegistryNumber;

        return $clone;
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

    /**
     * @return string
     */
    public function birthPlace(): string
    {
        return $this->birthPlace;
    }

    /**
     * @return Child
     */
    public function child(): Child
    {
        return $this->child;
    }

    public function companyNumber(): ?string
    {
        return $this->companyNumber ? $this->companyNumber->value() : null;
    }

    public function nationalRegistryNumber(): ?NationalRegistryNumber
    {
        return $this->nationalRegistryNumber;
    }
}
