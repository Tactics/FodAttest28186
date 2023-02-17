<?php

namespace Tactics\FodAttest28186\Model\Certifier;

use Assert\AssertionFailedException;
use Tactics\FodAttest28186\Enum\FodCertificationCode;
use Tactics\FodAttest28186\ValueObject\Address;
use Tactics\FodAttest28186\ValueObject\CompanyNumber;

/**
 * The authority who ensures the childcare is certified.
 * FodCertificationCode can be 0, 1, 2, 3, 4. Check the Enum to see what each code means.
 */
final class Certifier
{
    private string $name;

    private CompanyNumber $companyNumber;

    private Address $address;

    private FodCertificationCode $certificationCode;

    /**
     * @param string $name
     * @param CompanyNumber $companyNumber
     * @param Address $address
     * @param FodCertificationCode $certificationCode
     */
    private function __construct(
        string $name,
        CompanyNumber $companyNumber,
        Address $address,
        FodCertificationCode $certificationCode
    ) {
        $this->name = trim($name);
        $this->companyNumber = $companyNumber;
        $this->address = $address;
        $this->certificationCode = $certificationCode;
    }

    public static function create(
        string $name,
        CompanyNumber $companyNumber,
        Address $address,
        FodCertificationCode $certificationCode
    ): Certifier {
        return new self($name, $companyNumber, $address, $certificationCode);
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    public function companyNumber(): CompanyNumber
    {
        return $this->companyNumber;
    }

    public function address(): Address
    {
        return $this->address;
    }

    public function certificationCode(): FodCertificationCode
    {
        return $this->certificationCode;
    }

    /**
     * Helper function to easily create a Certifier for Opgroeien Regie (aka. Kind en gezin)
     * This is useful when this package is used in applications pertaining daycare.
     *
     * @return Certifier
     * @throws AssertionFailedException
     */
    public static function createForOpgroeienRegie(): Certifier
    {
        $address = Address::forBelgium('Hallepoortlaan 27', '1060', 'Sint-Gillis');
        $companyNumber = CompanyNumber::fromString('0886886638');
        return new self(
            'Opgroeien regie',
            $companyNumber,
            $address,
            FodCertificationCode::from(FodCertificationCode::OPGROEIEN_REGIE)
        );
    }
}
