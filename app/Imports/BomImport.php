<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class BomImport implements WithMultipleSheets, SkipsEmptyRows, WithEvents
    {
        protected $data = [];
        protected $headers = [];
        protected $rowCount = 0;
        protected $headerRowIndex = null;
        protected $stopRow = null;
        protected $worksheet = null;
        protected $spreadsheet = null;

        public function sheets(): array
        {
            return [
                0 => new FirstSheetImport($this)
            ];
        }

        public function registerEvents(): array
        {
            return [
                BeforeImport::class => function(BeforeImport $event) {
                    $reader = $event->getReader();
                    $this->spreadsheet = $reader->getDelegate();
                    $this->worksheet = $this->spreadsheet->getActiveSheet();
                    $this->locateHeaderRow();
                    $this->findStopRow();
                },
            ];
        }

        protected function locateHeaderRow()
        {
            $highestRow = $this->worksheet->getHighestRow();
            
            $headerKeywords = ['PART NO.', 'PART NO', 'PART #', 'SR NO.', 'ITEM CODE'];
            
            for ($row = 1; $row <= min(50, $highestRow); $row++) {
                $firstCell = trim($this->worksheet->getCell('A' . $row)->getCalculatedValue() ?? '');
                $firstCellUpper = strtoupper($firstCell);
                
                if (in_array($firstCellUpper, $headerKeywords)) {
                    $this->headerRowIndex = $row;
                    $this->headers = [];
                    
                    for ($col = 'A'; $col <= 'P'; $col++) {
                        $cellValue = trim($this->worksheet->getCell($col . $row)->getCalculatedValue() ?? '');
                        if (!empty($cellValue)) {
                            $this->headers[] = $cellValue;
                        }
                    }
                    break;
                }
            }
            
            if (!$this->headerRowIndex) {
                $row9Cell = trim($this->worksheet->getCell('A9')->getCalculatedValue() ?? '');
                if (strpos(strtoupper($row9Cell), 'PART') !== false) {
                    $this->headerRowIndex = 9;
                    for ($col = 'A'; $col <= 'P'; $col++) {
                        $cellValue = trim($this->worksheet->getCell($col . 9)->getCalculatedValue() ?? '');
                        if (!empty($cellValue)) {
                            $this->headers[] = $cellValue;
                        }
                    }
                }
            }
            
            if (!$this->headerRowIndex) {
                $this->headerRowIndex = 1;
            }
        }
        
        protected function findStopRow()
        {
            $highestRow = $this->worksheet->getHighestRow();
            
            for ($row = $this->headerRowIndex + 1; $row <= min($highestRow, 500); $row++) {
                $firstCell = trim($this->worksheet->getCell('A' . $row)->getCalculatedValue() ?? '');
                $firstCellUpper = strtoupper($firstCell);
                
                if (strpos($firstCellUpper, 'NOTE:') === 0 || 
                    strpos($firstCellUpper, 'NOTE') === 0 ||
                    strpos($firstCellUpper, 'PREPARED BY') !== false ||
                    strpos($firstCellUpper, 'CROSS REF') !== false ||
                    strpos($firstCellUpper, 'DESIGN ENGINEER') !== false) {
                    $this->stopRow = $row - 1;
                    break;
                }
            }
        }

        public function addRow($row, $rowNumber)
        {
            if ($rowNumber <= $this->headerRowIndex) {
                return;
            }
            
            if ($this->stopRow && $rowNumber > $this->stopRow) {
                return;
            }
            
            if ($this->worksheet) {
                for ($i = 0; $i < count($row); $i++) {
                    $colLetter = Coordinate::stringFromColumnIndex($i + 1);
                    $calculatedValue = $this->worksheet->getCell($colLetter . $rowNumber)->getCalculatedValue();
                    if ($calculatedValue !== null && $calculatedValue !== '') {
                        $row[$i] = $calculatedValue;
                    }
                }
            }
            
            $isEmpty = true;
            foreach ($row as $cell) {
                $cellValue = trim($cell ?? '');
                if (!empty($cellValue)) {
                    $isEmpty = false;
                    break;
                }
            }
            if ($isEmpty) {
                return;
            }
            
            $partNo = $this->cleanValue($row[0] ?? null);
            $description = $this->cleanValue($row[1] ?? null);
            $qty = $this->parseQuantity($row[5] ?? null);
            
            if (!empty($partNo) && (empty($qty) || $qty == 0)) {
                $descriptionUpper = strtoupper($description ?? '');
                if (strpos($descriptionUpper, 'ASSEMBLY') !== false || 
                    strpos($descriptionUpper, 'DETAILS') !== false ||
                    strpos($descriptionUpper, 'LIST') !== false) {
                    return;
                }
            }
            
            $partNoUpper = strtoupper($partNo ?? '');
            if (strpos($partNoUpper, 'NOTE') === 0 || 
                strpos($partNoUpper, 'CROSS') !== false ||
                strpos($partNoUpper, 'PREPARED') !== false ||
                strpos($partNoUpper, 'DESIGN') !== false) {
                return;
            }
            
            $this->rowCount++;
            
            $mappedRow = [
                'part_no' => $partNo,
                'part_discription' => $description,
                'part_code' => $this->cleanValue($row[2] ?? null),
                'material_specification' => $this->cleanValue($row[3] ?? null),
                'size_of_material' => $this->cleanValue($row[4] ?? null),
                'qty' => $qty > 0 ? $qty : 0,
                'unit' => $this->cleanValue($row[6] ?? null) ?: 'NOS',
                'purchase_technical_specification_no' => $this->cleanValue($row[7] ?? null),
                'stock_verification_yes/no' => $this->cleanValue($row[8] ?? null),
                'remarks' => $this->cleanValue($row[9] ?? null)
            ];
            
            $this->data[] = $mappedRow;
        }
        
        protected function cleanValue($value)
        {
            if ($value === null || $value === '') {
                return null;
            }
            
            if (is_numeric($value) && $value > 40000 && $value < 50000) {
                try {
                    return Date::excelToDateTimeObject($value)->format('Y-m-d');
                } catch (\Exception $e) {
                    return (string)$value;
                }
            }
            
            $cleaned = trim((string)$value);
            
            if (strlen($cleaned) > 500) {
                $cleaned = substr($cleaned, 0, 500);
            }
            
            return $cleaned;
        }
        
        protected function parseQuantity($value)
        {
            if ($value === null || $value === '') {
                return 0;
            }
            
            if (is_numeric($value)) {
                return (float)$value;
            }
            
            $cleaned = preg_replace('/[^0-9.]/', '', (string)$value);
            return (float)$cleaned;
        }

        public function getData()
        {
            return $this->data;
        }

        public function getHeaders()
        {
            return $this->headers;
        }

        public function getRowCount()
        {
            return $this->rowCount;
        }
    }