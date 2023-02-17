<?php

namespace Tactics\FodAttest28186\Model\Sender;

use Tactics\FodAttest28186\Enum\FodLanguageCode;

/**
 * A contact for a Company
 */
final class SenderContact
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var FodLanguageCode
     */
    private $languageCode;

    /**
     * @param string $name
     * @param FodLanguageCode $languageCode
     */
    public function __construct(string $name, FodLanguageCode $languageCode)
    {
        $this->name = trim($name);
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
