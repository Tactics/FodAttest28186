<?php

namespace Tactics\FodAttest28186\Entity\Child;

use Tactics\FodAttest28186\ValueObject\Address;
use Tactics\FodAttest28186\ValueObject\NationalRegistryNumber;

/**
 * The child who has attended the care.
 *
 */
final class ChildWithoutNationalRegistry implements Child
{
    private ChildData $data;

    /**
     * @param ChildData $data
     */
    public function __construct(ChildData $data)
    {
        $this->data = $data;
    }

    public static function createWithChildData(string $name, string $firstName, Address $address, \DateTimeImmutable $birthDate): self
    {
        $childData = new ChildData($name, $firstName, $address, $birthDate);

        return new self($childData);
    }

    public function nationalRegistryNumber(): ?NationalRegistryNumber
    {
        return null;
    }

    public function childData(): ChildData
    {
        return $this->data;
    }
}
