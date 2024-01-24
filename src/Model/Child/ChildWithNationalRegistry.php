<?php

namespace Tactics\FodAttest28186\Model\Child;

use Tactics\FodAttest28186\Exception\NonMatchingBirthdayException;
use Tactics\FodAttest28186\ValueObject\Address;
use Tactics\FodAttest28186\ValueObject\DayOfBirth;
use Tactics\FodAttest28186\ValueObject\NationalRegistryNumber;

/**
 * The child who has attended the care.
 *
 */
final class ChildWithNationalRegistry implements Child
{
    /**
     * @var NationalRegistryNumber
     */
    private $nationalRegistryNumber;
    /**
     * @var ChildDetails|null
     */
    private $details = null;
    /**
     * @var bool
     */
    private $severelyDisabled = false;
    /**
     * @var DayOfBirth
     */
    private $dayOfBirth;

    /**
     * @param NationalRegistryNumber $nationalRegistryNumber
     * @param DayOfBirth $dayOfBirth
     * @throws NonMatchingBirthdayException
     */
    private function __construct(
        NationalRegistryNumber $nationalRegistryNumber,
        DayOfBirth  $dayOfBirth
    ) {
        $this->nationalRegistryNumber = $nationalRegistryNumber;
        $this->dayOfBirth = $dayOfBirth;

        try {
            $dayOfBirthFromNationalRegistryNumber = $this->nationalRegistryNumber->dayOfBirth();
        } catch (\Throwable $exception) {
            $dayOfBirthFromNationalRegistryNumber = null;
        }

        if (($dayOfBirthFromNationalRegistryNumber instanceof DayOfBirth) && !$dayOfBirthFromNationalRegistryNumber->isSameDay($this->dayOfBirth->toPhpDateTime())) {
            Throw new NonMatchingBirthdayException('Given birthday does not match with day of birth found in national registry number');
        }
    }

    public static function create(
        NationalRegistryNumber $nationalRegistryNumber,
        DayOfBirth  $dayOfBirth
    ): self {
        return new self($nationalRegistryNumber, $dayOfBirth);
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
            $this->dayOfBirth()
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
        return $this->dayOfBirth;
    }
}
