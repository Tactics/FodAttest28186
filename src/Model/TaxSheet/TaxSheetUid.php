<?php

namespace Tactics\FodAttest28186\Model\TaxSheet;

use Assert\Assertion;
use Assert\AssertionFailedException;

final class TaxSheetUid
{
    private string $uid;

    /**
     * @throws AssertionFailedException
     */
    private function __construct(
        string $uid
    ) {
        Assertion::minLength($uid, 1);
        Assertion::maxLength($uid, 20);
        $this->uid = trim($uid);
    }

    /**
     * @throws AssertionFailedException
     */
    public static function fromString(string $uid): TaxSheetUid
    {
        return new self($uid);
    }

    public function toString(): string
    {
        return $this->uid;
    }
}
