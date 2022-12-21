<?php

use Tactics\FodAttest28186\Entity\Debtor\Debtor;
use Tactics\FodAttest28186\Enum\FodSheetType;

class Sheet
{
    private string $externalIdentifier;

    private Debtor $debtor;

    private FodSheetType $sheetType;

    /**
     * @param string $externalIdentifier
     * @param Debtor $debtor
     * @param FodSheetType $sheetType
     */
    public function __construct(string $externalIdentifier, Debtor $debtor, FodSheetType $sheetType)
    {
        $this->externalIdentifier = $externalIdentifier;
        $this->debtor = $debtor;
        $this->sheetType = $sheetType;
    }

    /**
     * @return string
     */
    public function externalIdentifier(): string
    {
        return $this->externalIdentifier;
    }

    /**
     * @return Debtor
     */
    public function debtor(): Debtor
    {
        return $this->debtor;
    }

    /**
     * @return FodSheetType
     */
    public function sheetType(): FodSheetType
    {
        return $this->sheetType;
    }
}
