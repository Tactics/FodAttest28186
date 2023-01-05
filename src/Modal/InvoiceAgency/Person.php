<?php

namespace Tactics\FodAttest28186\Modal\InvoiceAgency;

use Tactics\FodAttest28186\Modal\Certifier\Certifier;
use Tactics\FodAttest28186\ValueObject\Address;
use Tactics\FodAttest28186\ValueObject\NationalRegistryNumber;

/**
 * The person who provided the care could be a regular person, who does not have Company Number.
 * In those cases it should be a Person instead of Company
 */
final class Person implements InvoiceAgency
{
    private string $name;

    private Address $address;

    private NationalRegistryNumber $nationalRegistryNumber;

    private ?Certifier $certifier = null;

    /**
     * @param string $name
     * @param Address $address
     * @param NationalRegistryNumber $nationalRegistryNumber
     */
    private function __construct(string $name, Address $address, NationalRegistryNumber $nationalRegistryNumber)
    {
        $this->name = $name;
        $this->address = $address;
        $this->nationalRegistryNumber = $nationalRegistryNumber;
    }

    public static function create(
        string $name,
        Address $address,
        NationalRegistryNumber $nationalRegistryNumber
    ) : Person
    {
        return new self($name, $address, $nationalRegistryNumber);
    }

    public function withCertifier(Certifier $certifier): Person
    {
        $clone = clone $this;
        $clone->certifier = $certifier;

        return $clone;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function address(): Address
    {
        return $this->address;
    }

    public function identifier(): string
    {
        return $this->nationalRegistryNumber->value();
    }

    public function certifier(): ?Certifier
    {
        return $this->certifier;
    }
}
