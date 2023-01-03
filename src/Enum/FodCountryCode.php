<?php

namespace Tactics\FodAttest28186\Enum;

use InvalidArgumentException;

/**
 * NIS-code for countries.
 * When in need of other countries you can always find the country, and it's NIS-code in following Excel file:
 * https://statbel.fgov.be/sites/default/files/Over_Statbel_FR/Nomenclaturen/Authentieke%20Bron%20Landencodes.xlsx
 */
final class FodCountryCode extends Enum
{
    public const BELGIUM = 150;

    public const NETHERLANDS = 129;

    public const GERMANY = 103;

    public static function fromIsoCode(string $isoCode): FodCountryCode
    {
        switch (strtolower($isoCode)) {
            case 'be':
                return self::from(self::BELGIUM);
            case 'nl':
                return self::from(self::NETHERLANDS);
            case 'de':
                return self::from(self::GERMANY);
            default:
                throw new InvalidArgumentException(sprintf('iso code %s not supported', $isoCode));
        }
    }
}
