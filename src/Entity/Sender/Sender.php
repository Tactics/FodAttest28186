<?php

namespace Tactics\FodAttest28186\Entity\Sender;

use Tactics\FodAttest28186\Enum\FodLanguageCode;
use Tactics\FodAttest28186\ValueObject\Address;

interface Sender
{
    public function name(): string;

    public function address(): Address;

    public function phoneNumber(): string;

    public function email(): string;

    public function identifier(): string;

    public function contactLanguageCode(): FodLanguageCode;

    public function contactName(): string;
}
