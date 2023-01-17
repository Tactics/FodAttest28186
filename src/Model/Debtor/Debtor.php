<?php

namespace Tactics\FodAttest28186\Model\Debtor;

use Tactics\FodAttest28186\ValueObject\Address;
use Tactics\FodAttest28186\ValueObject\CompanyNumber;
use Tactics\FodAttest28186\ValueObject\NationalRegistryNumber;

/**
 * The person who has paid for the care
 */
final class Debtor
{
    private ?DebtorDetails $details = null;

    private ?CompanyNumber $companyNumber = null;

    private NationalRegistryNumber $nationalRegistryNumber;

    /**
     * @param NationalRegistryNumber $nationalRegistryNumber
     */
    private function __construct(
        NationalRegistryNumber $nationalRegistryNumber
    ) {
        $this->nationalRegistryNumber = $nationalRegistryNumber;
    }

    public static function create(
        NationalRegistryNumber $nationalRegistryNumber
    ): self {
        return new self($nationalRegistryNumber);
    }

    public function withDetails(
        string $familyName,
        string $givenName,
        Address $address,
        string $placeOfBirth
    ): Debtor {
        $new = clone ($this);
        $details = DebtorDetails::create(
            $familyName,
            $givenName,
            $address,
            $this->nationalRegistryNumber->dayOfBirth(),
            $placeOfBirth
        );
        $new->details = $details;
        return $new;
    }

    public function withCompanyNumber(CompanyNumber $companyNumber): Debtor
    {
        $clone = clone $this;
        $clone->companyNumber = $companyNumber;

        return $clone;
    }

    public function nationalRegistryNumber(): NationalRegistryNumber
    {
        return $this->nationalRegistryNumber;
    }

    public function companyNumber(): ?string
    {
        return $this->companyNumber ? $this->companyNumber->value() : null;
    }

    public function equals(Debtor $debtor): bool
    {
        return $this->nationalRegistryNumber->value() === $debtor->nationalRegistryNumber->value();
    }

    public function details(): ?DebtorDetails
    {
        return $this->details;
    }
}
