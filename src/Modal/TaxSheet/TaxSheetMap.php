<?php

namespace Tactics\FodAttest28186\Modal\TaxSheet;

use Generator;

final class TaxSheetMap
{
    /**
     * @var TaxSheet[]
     */
    private array $sheets = [];

    private function __construct()
    {
    }

    public static function create(): TaxSheetMap
    {
        return new self();
    }

    public function add(TaxSheet $sheet): TaxSheetMap
    {
        $new = clone ($this);
        $sheets = $this->sheets;
        $sheets[$sheet->uuid()->toString()] = $sheet;
        $new->sheets = $sheets;
        return $new;
    }

    public function lookUp(
        TaxSheetUuid $uuid
    ): ?TaxSheet {
        return $this->sheets[$uuid->toString()] ?? null;
    }

    public function replace(
        TaxSheetUuid $uuid,
        TaxSheet $sheet
    ): TaxSheetMap {
        $new = clone ($this);

        $sheets = $this->sheets;
        $sheets[$uuid->toString()] = $sheet;
        $new->sheets = $sheets;
        return $new;
    }

    /**
     * @return Generator
     */
    public function getIterator(): Generator
    {
        foreach ($this->sheets as $fiche) {
            yield $fiche;
        }
    }
}
