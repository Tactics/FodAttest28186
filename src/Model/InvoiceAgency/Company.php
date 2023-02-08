<?php

namespace Tactics\FodAttest28186\Model\InvoiceAgency;

use Tactics\FodAttest28186\Model\Certifier\Certifier;
use Tactics\FodAttest28186\ValueObject\Address;
use Tactics\FodAttest28186\ValueObject\CompanyNumber;

/**
 * The instance to whom the care was paid. Also known as the organiser.
 * When the care is paid to an entity other than the one providing the care,
 * it is the entity receiving the payment that must issue the certificate.
 */
final class Company implements InvoiceAgency
{
    private string $name;

    private Address $address;

    private CompanyNumber $companyNumber;

    private ?Certifier $certifier = null;

    /**
     * @param string $name
     * @param Address $address
     * @param CompanyNumber $companyNumber
     */
    private function __construct(string $name, Address $address, CompanyNumber $companyNumber)
    {
        $this->name = $name;
        $this->address = $address;
        $this->companyNumber = $companyNumber;
    }

    public static function create(
        string $name,
        Address $address,
        CompanyNumber $companyNumber
    ): Company {
        return new self($name, $address, $companyNumber);
    }

    public function withCertifier(Certifier $certifier): self
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
        return $this->companyNumber->value();
    }

    public function certifier(): ?Certifier
    {
        return $this->certifier;
    }
}
