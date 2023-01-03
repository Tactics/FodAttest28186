<?php

use Tactics\FodAttest28186\Entity\Debtor\Debtor;
use Tactics\FodAttest28186\Entity\Tariff\TariffGrouping;
use Tactics\FodAttest28186\Enum\FodSheetType;

final class Sheet
{
    private string $externalIdentifier;

    private Debtor $debtor;

    private TariffGrouping $tariffGrouping;

    private FodSheetType $sheetType;

    /**
     * @param string $externalIdentifier
     * @param Debtor $debtor
     * @param FodSheetType $sheetType
     * @param TariffGrouping $tariffGrouping
     */
    public function __construct(string $externalIdentifier, Debtor $debtor, FodSheetType $sheetType, TariffGrouping $tariffGrouping)
    {
        $this->externalIdentifier = $externalIdentifier;
        $this->debtor = $debtor;
        $this->sheetType = $sheetType;
        $this->tariffGrouping = $tariffGrouping;
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

    /**
     * @return TariffGrouping
     */
    public function tariffGrouping(): TariffGrouping
    {
        return $this->tariffGrouping;
    }
}
