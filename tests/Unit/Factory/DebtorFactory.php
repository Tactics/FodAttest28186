<?php

namespace Tests\Unit\Factory;

use Tactics\FodAttest28186\Modal\Debtor\Debtor;
use Tactics\FodAttest28186\ValueObject\NationalRegistryNumber;

final class DebtorFactory
{
    public function __construct()
    {
    }

    public function create(): Debtor
    {
        $rrn = NationalRegistryNumber::fromString('65.03.06-006.36');
        return Debtor::create(
            $rrn
        );
    }
}
