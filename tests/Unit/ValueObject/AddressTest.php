<?php

namespace Tests\Unit\ValueObject;

use PHPUnit\Framework\TestCase;
use Tactics\FodAttest28186\Enum\FodCountryCode;
use Tactics\FodAttest28186\ValueObject\Address;
use Tactics\FodAttest28186\ValueObject\NationalRegistryNumber;
use TypeError;

final class AddressTest extends TestCase
{

    public function testCreateValidAddressInBelgium(): void {
        $address = Address::forBelgium('Starrenhoflaan 14', '2920', 'Kapellen');
        $this->assertEquals(FodCountryCode::BELGIUM, $address->countryCode()->value());
        $this->assertEquals('2920', $address->postal());
    }

    public function testAddressInBelgiumValidatesPostalCode(): void {
        $this->expectException(TypeError::class);
        Address::forBelgium('Starrenhoflaan 14', '10900', 'Kapellen');
    }

    public function testCreateValidForeignAddress(): void {
        $address = Address::forForeign('Time Square', '10036', 'New York', FodCountryCode::fromIsoCode('us'));
        $this->assertEquals(FodCountryCode::USA, $address->countryCode()->value());
        $this->assertEquals('10036', $address->postal());
    }

    public function testCreateInvalidForeignAddress(): void {
        $this->expectException(TypeError::class);
        Address::forForeign('Time Square', '1234567891123', 'New York', FodCountryCode::fromIsoCode('us'));
    }
}
