<?php

namespace Tactics\FodAttest28186\Entity\Sender;

use Tactics\FodAttest28186\Enum\FodLanguageCode;

/**
 * A contact for a Company
 */
final class SenderContact
{
    private string $name;

    private FodLanguageCode $languageCode;

    /**
     * @param string $name
     * @param FodLanguageCode $languageCode
     */
    public function __construct(string $name, FodLanguageCode $languageCode)
    {
        $this->name = $name;
        $this->languageCode = $languageCode;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    public function languageCode(): FodLanguageCode
    {
        return $this->languageCode;
    }
}
