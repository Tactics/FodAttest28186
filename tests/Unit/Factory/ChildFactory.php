<?php

namespace Tests\Unit\Factory;

use Tactics\FodAttest28186\Enum\FodCountryCode;
use Tactics\FodAttest28186\Modal\Child\ChildWithoutNationalRegistry;
use Tactics\FodAttest28186\ValueObject\Address;
use Tactics\FodAttest28186\ValueObject\DayOfBirth;

final class ChildFactory
{
    public function __construct(){}

    public function create(bool $disable, DayOfBirth $dayOfBirth): ChildWithoutNationalRegistry
    {
        $address = Address::forForeign(
            'Sesame Street 123',
            '10123',
            'Manhattan',
            FodCountryCode::from(FodCountryCode::USA)
        );

        $child = ChildWithoutNationalRegistry::create(
            'Doe',
            'Jane',
            $address,
            $dayOfBirth
        );

        if ($disable) {
            $child = $child->withSevereDisability();
        }

        return $child;
    }

}
