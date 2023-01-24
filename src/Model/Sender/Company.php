<?php

namespace Tactics\FodAttest28186\Model\Sender;

use Tactics\FodAttest28186\Enum\FodLanguageCode;
use Tactics\FodAttest28186\ValueObject\Address;
use Tactics\FodAttest28186\ValueObject\CompanyNumber;

/**
 * The instance that is sending the data to the FOD.
 * 99% of the time this is the same instance as the InvoiceAgency
 */
final class Company implements Sender
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Address
     */
    private $address;

    /**
     * @var string
     */
    private $phoneNumber;

    /**
     * @var string
     */
    private $email;

    /**
     * @var CompanyNumber
     */
    private $companyNumber;

    /**
     * @var SenderContact
     */
    private $senderContact;

    /**
     * @param string $name
     * @param Address $address
     * @param string $phoneNumber
     * @param string $email
     * @param CompanyNumber $companyNumber
     * @param SenderContact $senderContact
     */
    private function __construct(string $name, Address $address, string $phoneNumber, string $email, CompanyNumber $companyNumber, SenderContact $senderContact)
    {
        $this->name = $name;
        $this->address = $address;
        $this->phoneNumber = $phoneNumber;
        $this->email = $email;
        $this->companyNumber = $companyNumber;
        $this->senderContact = $senderContact;
    }

    public static function create(
        string $name,
        Address $address,
        string $phoneNumber,
        string $email,
        CompanyNumber $companyNumber,
        SenderContact $senderContact
    ): Company {
        return new self($name, $address, $phoneNumber, $email, $companyNumber, $senderContact);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function address(): Address
    {
        return $this->address;
    }

    public function phoneNumber(): string
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
}
