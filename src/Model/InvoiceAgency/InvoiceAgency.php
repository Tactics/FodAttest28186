<?php

namespace Tactics\FodAttest28186\Model\InvoiceAgency;

use Tactics\FodAttest28186\Model\Certifier\Certifier;
use Tactics\FodAttest28186\ValueObject\Address;

interface InvoiceAgency
{
    public function name(): string;

    public function address(): Address;

    public function identifier(): string;

    public function withCertifier(Certifier $certifier): self;

    public function certifier(): ?Certifier;
}
