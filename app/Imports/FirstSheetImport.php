<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithStartRow;

class FirstSheetImport implements ToArray, WithStartRow
{
    protected $parent;
    protected $startRow = 1;


    public function __construct($parent)
    {
        $this->parent = $parent;
    }

    public function startRow(): int
    {
        return $this->startRow;
    }

    public function setStartRow($startRow)
    {
        $this->startRow = $startRow;
    }

    public function array(array $array)
    {
        foreach ($array as $rowNumber => $row) {
            $this->parent->addRow($row, $rowNumber + $this->startRow);
        }
    }
}