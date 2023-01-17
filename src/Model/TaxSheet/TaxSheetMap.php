<?php

namespace Tactics\FodAttest28186\Model\TaxSheet;

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
        $sheets[$sheet->uid()->toString()] = $sheet;
        $new->sheets = $sheets;
        return $new;
    }

    public function lookUp(
        TaxSheetUid $uid
    ): ?TaxSheet {
        return $this->sheets[$uid->toString()] ?? null;
    }

    public function replace(
        TaxSheetUid $uid,
        TaxSheet $sheet
    ): TaxSheetMap {
        $new = clone ($this);

        $sheets = $this->sheets;
        $sheets[$uid->toString()] = $sheet;
        $new->sheets = $sheets;
        return $new;
    }

    /**
     * @return Generator<TaxSheet>
     */
    public function getIterator(): Generator
    {
        foreach ($this->sheets as $fiche) {
            yield $fiche;
        }
    }
}
