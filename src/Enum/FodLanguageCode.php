<?php

namespace Tactics\FodAttest28186\Enum;

use InvalidArgumentException;

final class FodLanguageCode extends Enum
{
    public const DUTCH = 1;

    public const FRENCH = 2;

    public const GERMAN = 3;

    public static function fromLanguage(string $lang): FodLanguageCode
    {
        switch (strtolower($lang)) {
            case 'nl':
                return self::from(self::DUTCH);
            case 'fr':
                return self::from(self::FRENCH);
            case 'de':
                return self::from(self::GERMAN);
            default:
                throw new InvalidArgumentException(sprintf('language code %s not supported', $lang));
        }
    }
}
