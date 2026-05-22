<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SampleBomTemplate implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            [
                'PART-001',
                'Sample Part Description',
                'SA-001',
                'SA 516 Gr.70',
                'OD 100 x 10 THK',
                '10',
                'NOS',
                'PTS-001',
                'Yes',
                'Sample remark'
            ],
            [
                'PART-002',
                'Another Sample Part',
                'SA-002',
                'SS 304',
                'OD 50 x 5 THK',
                '5',
                'NOS',
                'PTS-002',
                'No',
                'Critical item'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'PART NO.',
            'PART DISCRIPTION',
            'PART CODE',
            'MATERIAL SPECIFICATION',
            'SIZE OF MATERIAL',
            'QTY.',
            'UNIT',
            'PURCHASE TECHNICAL SPECIFICATION No.',
            'STOCK VERIFICATION YES/NO',
            'REMARKS'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}