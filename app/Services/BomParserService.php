<?php

namespace App\Services;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BomImport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BomParserService
{
    public function parse($filePath, $originalName)
    {
        try {
            // Get full storage path
            $fullPath = Storage::disk('public')->path($filePath);
            
            if (!file_exists($fullPath)) {
                // Try private disk
                $fullPath = Storage::path($filePath);
            }
            
            if (!file_exists($fullPath)) {
                return [
                    'success' => false,
                    'error' => 'File not found: ' . $filePath
                ];
            }
            
            $import = new BomImport();
            Excel::import($import, $fullPath);
            
            $data = $import->getData();
            
            // Filter out empty rows and header rows
            $filteredData = array_filter($data, function($row) {
                // Skip rows that are headers or empty
                if (empty($row)) return false;
                if (isset($row['part_no']) && strpos(strtoupper($row['part_no']), 'PART NO.') !== false) return false;
                if (isset($row['part_discription']) && empty($row['part_discription']) && empty($row['part_no'])) return false;
                return true;
            });
            
            return [
                'success' => true,
                'data' => array_values($filteredData),
                'headers' => $import->getHeaders(),
                'row_count' => count($filteredData)
            ];
        } catch (\Exception $e) {
            Log::error('BOM Parsing Failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Failed to parse BOM file: ' . $e->getMessage()
            ];
        }
    }

    public function validateStructure($filePath)
    {
        try {
            $fullPath = Storage::disk('public')->path($filePath);
            
            if (!file_exists($fullPath)) {
                $fullPath = Storage::path($filePath);
            }
            
            if (!file_exists($fullPath)) {
                return ['valid' => false, 'error' => 'File not found'];
            }
            
            // Load spreadsheet
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fullPath);
            $worksheet = $spreadsheet->getActiveSheet();
            
            // Look for the BOM data header row
            $highestRow = $worksheet->getHighestRow();
            $foundHeaderRow = false;
            $headerRowIndex = null;
            
            // Keywords that indicate the BOM data header
            $headerKeywords = ['PART NO.', 'PART NO', 'PART #', 'SR NO.', 'ITEM CODE', 'PART DISCRIPTION'];
            
            for ($row = 1; $row <= min(50, $highestRow); $row++) {
                $firstCell = trim($worksheet->getCell('A' . $row)->getValue() ?? '');
                $firstCellUpper = strtoupper($firstCell);
                
                if (in_array($firstCellUpper, $headerKeywords) || 
                    (strpos($firstCellUpper, 'PART') !== false && strpos($firstCellUpper, 'NO') !== false)) {
                    $foundHeaderRow = true;
                    $headerRowIndex = $row;
                    break;
                }
            }
            
            if (!$foundHeaderRow) {
                // Try to find by checking row 9 which might contain the header in your BOM
                $row9Cell = trim($worksheet->getCell('A9')->getValue() ?? '');
                if (strpos(strtoupper($row9Cell), 'PART') !== false) {
                    $foundHeaderRow = true;
                    $headerRowIndex = 9;
                }
            }
            
            if (!$foundHeaderRow) {
                return ['valid' => false, 'error' => 'Could not find BOM data header row. Looked for: PART NO., ITEM CODE, etc.'];
            }
            
            return ['valid' => true, 'header_row' => $headerRowIndex];
        } catch (\Exception $e) {
            Log::error('BOM Validation Failed: ' . $e->getMessage());
            return ['valid' => false, 'error' => 'Invalid file format: ' . $e->getMessage()];
        }
    }
}