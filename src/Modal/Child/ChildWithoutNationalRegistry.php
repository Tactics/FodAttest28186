<?php

namespace Tactics\FodAttest28186\Modal\Child;

use Tactics\FodAttest28186\ValueObject\Address;
use Tactics\FodAttest28186\ValueObject\DayOfBirth;
use Tactics\FodAttest28186\ValueObject\NationalRegistryNumber;

/**
 * The child who has attended the care.
 *
 */
final class ChildWithoutNationalRegistry implements Child
{
    private ChildDetails $details;
    /**
     * @var true
     */
    private bool $severelyDisabled = false;

    /**
     * @param ChildDetails $details
     */
    private function __construct(ChildDetails $details)
    {
        $this->details = $details;
    }

    public static function create(
        string $familyName,
        string $givenName,
        Address $address,
        DayOfBirth $birthDate
    ): ChildWithoutNationalRegistry {
        $childData = ChildDetails::create($familyName, $givenName, $address, $birthDate);
        return new self($childData);
    }

    public function withSevereDisability(): ChildWithoutNationalRegistry
    {
        $new = clone ($this);
        $new->severelyDisabled = true;
        return $new;
    }

    public function nationalRegistryNumber(): ?NationalRegistryNumber
    {
        return null;
    }

    public function details(): ChildDetails
    {
        return $this->details;
    }

    public function toUniqueIdentifiable(): string
    {
        $props = [
            $this->details()->givenName(),
            $this->details()->familyName(),
            $this->details()->address()->addressLine(),
            $this->details()->address()->city(),
            $this->details()->address()->postal(),
            $this->details()->address()->countryCode(),
            $this->details()->dayOfBirth()->format()
        ];
        return md5(serialize($props));
    }

    public function equals(Child $child): bool
    {
        return $this->toUniqueIdentifiable() === $child->toUniqueIdentifiable();
    }

    public function isSeverelyDisabled(): bool
    {
        return $this->severelyDisabled;
    }

    public function dayOfBirth(): DayOfBirth
    {
        return $this->details()->dayOfBirth();
    }
}
