<?php

namespace Tests\Unit\Factory;

use Tactics\FodAttest28186\Model\Debtor\Debtor;
use Tactics\FodAttest28186\ValueObject\NationalRegistryNumber;

final class DebtorFactory
{
    public function __construct()
    {
    }

    public function create(string $nr): Debtor
    {
        $rrn = NationalRegistryNumber::fromString($nr);
        return Debtor::create(
            $rrn
        );
    }
}
