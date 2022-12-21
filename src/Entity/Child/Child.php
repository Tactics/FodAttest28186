<?php

namespace Tactics\FodAttest28186\Entity\Child;

use Tactics\FodAttest28186\ValueObject\NationalRegistryNumber;

/**
 * Interface because we need to support PHP7.4, and we can not use Union types
 */
interface Child
{
    public function nationalRegistryNumber(): ?NationalRegistryNumber;

    public function childData(): ?ChildData;
}
