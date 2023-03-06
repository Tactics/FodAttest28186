<?php

namespace Tactics\FodAttest28186\Model\Sender;

use Tactics\FodAttest28186\Enum\FodLanguageCode;
use Tactics\FodAttest28186\ValueObject\Address;
use Tactics\FodAttest28186\ValueObject\NationalRegistryNumber;
use Tactics\FodAttest28186\ValueObject\PhoneNumber;

/**
 * The instance that is sending the data to the FOD.
 * Could be a Person without a Company Number doing this on behalf of someone else
 */
final class Person implements Sender
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
     * @var PhoneNumber
     */
    private $phoneNumber;

    /**
     * @var string
     */
    private $email;

    /**
     * @var NationalRegistryNumber
     */
    private $nationalRegistryNumber;

    /**
     * @var FodLanguageCode
     */
    private $languageCode;

    /**
     * @param string $name
     * @param Address $address
     * @param PhoneNumber $phoneNumber
     * @param string $email
     * @param NationalRegistryNumber $nationalRegistryNumber
     * @param FodLanguageCode $languageCode
     */
    public function __construct(string $name, Address $address, PhoneNumber $phoneNumber, string $email, NationalRegistryNumber $nationalRegistryNumber, FodLanguageCode $languageCode)
    {
        $this->name = trim($name);
        $this->address = $address;
        $this->phoneNumber = $phoneNumber;
        $this->email = trim($email);
        $this->nationalRegistryNumber = $nationalRegistryNumber;
        $this->languageCode = $languageCode;
    }

    public static function create(
        string $name,
        Address $address,
        PhoneNumber $phoneNumber,
        string $email,
        NationalRegistryNumber $nationalRegistryNumber,
        FodLanguageCode $languageCode
    ): Person {
        return new self($name, $address, $phoneNumber, $email, $nationalRegistryNumber, $languageCode);
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
        return $this->nationalRegistryNumber->value();
    }

    public function contactLanguageCode(): FodLanguageCode
    {
        return $this->languageCode;
    }

    public function contactName(): string
    {
        return $this->name;
    }
}
