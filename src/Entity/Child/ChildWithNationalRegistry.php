<?php

namespace Tactics\FodAttest28186\Entity\Child;

use Tactics\FodAttest28186\ValueObject\NationalRegistryNumber;

/**
 * The child who has attended the care.
 *
 */
final class ChildWithNationalRegistry implements Child
{
    private NationalRegistryNumber $nationalRegistryNumber;

    /**
     * @param NationalRegistryNumber|null $nationalRegistryNumber
     */
    public function __construct(NationalRegistryNumber $nationalRegistryNumber)
    {
        $this->nationalRegistryNumber = $nationalRegistryNumber;
    }


    public function nationalRegistryNumber(): NationalRegistryNumber
    {
        return $this->nationalRegistryNumber;
    }

    public function childData(): ?ChildData
    {
        return null;
    }
}
