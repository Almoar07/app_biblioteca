<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;



abstract class BaseStyledExport implements WithStyles, ShouldAutoSize
{
    public function styles(Worksheet $sheet)
    {
        // Estilo encabezado A1:Fx (dinámico según cantidad de columnas)
        $lastColumn = $sheet->getHighestColumn(); // Ej: "F"
        $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1F4E78'],
            ],
        ]);

        // Estilo del cuerpo
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle("A2:{$lastColumn}{$highestRow}")->applyFromArray([
            'font' => ['name' => 'Calibri', 'size' => 11],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'CCCCCC'],
                ],
            ],
        ]);

        // Estilo condicional: resaltar celda en rojo si valor en columna F es 0
        for ($row = 2; $row <= $highestRow; $row++) {
            $cell = "F$row";
            $value = $sheet->getCell($cell)->getValue();
            if ($value === '0' || $value === 0) {
                $sheet->getStyle($cell)->applyFromArray([
                    'font' => ['color' => ['rgb' => 'FF0000']],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFEAEA'],
                    ],
                ]);
            }
        }

        return [];
    }
}
