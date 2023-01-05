<?php

namespace Tactics\FodAttest28186\Modal\InvoiceAgency;

use Tactics\FodAttest28186\Modal\Certifier\Certifier;
use Tactics\FodAttest28186\ValueObject\Address;

interface InvoiceAgency
{
    public function name(): string;

    public function address(): Address;

    public function identifier(): string;

    public function certifier(): ?Certifier;
}
