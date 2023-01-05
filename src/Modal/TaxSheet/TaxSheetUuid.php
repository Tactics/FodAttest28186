<?php

namespace Tactics\FodAttest28186\Modal\TaxSheet;

use Assert\Assertion;
use Assert\AssertionFailedException;

final class TaxSheetUuid
{

    private string $uuid;

    /**
     * @throws AssertionFailedException
     */
    private function __construct(
       string $uuid
    ) {
        Assertion::uuid($uuid);
        $this->uuid = $uuid;
    }

    /**
     * @throws AssertionFailedException
     */
    public static function fromString(string $uuid): TaxSheetUuid
    {
        return new self($uuid);
    }

    public function toString(): string
    {
        return $this->uuid;
    }

}
