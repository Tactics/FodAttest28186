<?php

namespace Tactics\FodAttest28186\Model\Sender;

use Tactics\FodAttest28186\Enum\FodLanguageCode;
use Tactics\FodAttest28186\ValueObject\Address;
use Tactics\FodAttest28186\ValueObject\CompanyNumber;
use Tactics\FodAttest28186\ValueObject\DivisionNumber;
use Tactics\FodAttest28186\ValueObject\PhoneNumber;

/**
 * The instance that is sending the data to the FOD.
 * 99% of the time this is the same instance as the InvoiceAgency
 */
final class Company implements Sender
{
    private string $name;

    private Address $address;

    private PhoneNumber $phoneNumber;

    private string $email;

    private CompanyNumber $companyNumber;

    private SenderContact $senderContact;

    private ?DivisionNumber $division = null;

    /**
     * @param string $name
     * @param Address $address
     * @param string $phoneNumber
     * @param string $email
     * @param CompanyNumber $companyNumber
     * @param SenderContact $senderContact
     */
    private function __construct(string $name, Address $address, PhoneNumber $phoneNumber, string $email, CompanyNumber $companyNumber, SenderContact $senderContact)
    {
        $this->name = trim($name);
        $this->address = $address;
        $this->phoneNumber = $phoneNumber;
        $this->email = trim($email);
        $this->companyNumber = $companyNumber;
        $this->senderContact = $senderContact;
    }

    public static function create(
        string $name,
        Address $address,
        PhoneNumber $phoneNumber,
        string $email,
        CompanyNumber $companyNumber,
        SenderContact $senderContact
    ): Company {
        return new self($name, $address, $phoneNumber, $email, $companyNumber, $senderContact);
    }

    public function withDivision(DivisionNumber $division): self
    {
        $clone = clone $this;
        $clone->division = $division;

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

    public function phoneNumber(): PhoneNumber
    {
        return $this->phoneNumber;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function identifier(): string
    {
        return $this->companyNumber->value();
    }

    public function contactLanguageCode(): FodLanguageCode
    {
        return $this->senderContact->languageCode();
    }

    public function contactName(): string
    {
        return $this->senderContact->name();
    }

    public function division(): ?DivisionNumber
    {
        return $this->division;
    }
}
