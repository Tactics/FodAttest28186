<?php

namespace Tactics\FodAttest28186\Model\Child;

use Tactics\FodAttest28186\ValueObject\Address;
use Tactics\FodAttest28186\ValueObject\DayOfBirth;
use Tactics\FodAttest28186\ValueObject\NationalRegistryNumber;

/**
 * The child who has attended the care.
 *
 */
final class ChildWithNationalRegistry implements Child
{
    private NationalRegistryNumber $nationalRegistryNumber;
    private ?ChildDetails $details = null;
    private bool $severelyDisabled = false;

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

    public function withSevereDisability(): ChildWithNationalRegistry
    {
        $new = clone ($this);
        $new->severelyDisabled = true;
        return $new;
    }

    public function withDetails(
        string $familyName,
        string $givenName,
        Address $address
    ): ChildWithNationalRegistry {
        $new = clone ($this);
        $details = ChildDetails::create(
            $familyName,
            $givenName,
            $address,
            $this->nationalRegistryNumber->dayOfBirth()
        );
        $new->details = $details;
        return $new;
    }

    public function nationalRegistryNumber(): NationalRegistryNumber
    {
        return $this->nationalRegistryNumber;
    }

    public function details(): ?ChildDetails
    {
        return $this->details;
    }

    public function equals(Child $child): bool
    {
        return $this->toUniqueIdentifiable() === $child->toUniqueIdentifiable();
    }

    public function isSeverelyDisabled(): bool
    {
        return $this->severelyDisabled;
    }

    public function toUniqueIdentifiable(): string
    {
        return md5(serialize([$this->nationalRegistryNumber()->value()]));
    }

    public function dayOfBirth(): DayOfBirth
    {
        return $this->nationalRegistryNumber->dayOfBirth();
    }
}
