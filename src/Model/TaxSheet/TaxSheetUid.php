<?php

namespace Tactics\FodAttest28186\Model\TaxSheet;

use Assert\Assertion;
use Assert\AssertionFailedException;

final class TaxSheetUid
{
    /**
     * @var string
     */
    private $uid;

    /**
     * @throws AssertionFailedException
     */
    private function __construct(
        string $uid
    ) {
        Assertion::maxLength($uid, 20);
        $this->uid = $uid;
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
