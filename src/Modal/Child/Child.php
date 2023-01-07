<?php

namespace Tactics\FodAttest28186\Modal\Child;

use Tactics\FodAttest28186\ValueObject\DayOfBirth;
use Tactics\FodAttest28186\ValueObject\NationalRegistryNumber;

/**
 * Interface because we need to support PHP7.4, and we can not use Union types
 **/
interface Child
{
    public function nationalRegistryNumber(): ?NationalRegistryNumber;

    public function details(): ?ChildDetails;

    public function equals(Child $child): bool;

    public function isSeverelyDisabled(): bool;

    public function toUniqueIdentifiable(): string;

    public function dayOfBirth(): DayOfBirth;
}
