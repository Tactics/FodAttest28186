<?php

class SheetCollection
{
    private array $collection;

    public function __construct()
    {
        $this->collection = [];
    }

    public function add(Sheet $tariff): void
    {
        $this->collection[] = $tariff;
    }

    /**
     * @return Sheet[]
     */
    public function collection(): array
    {
        return $this->collection;
    }
}
