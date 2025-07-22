<?php

namespace App\Exports;

use App\Models\Libro;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class StockPorLibroExport extends BaseStyledExport implements FromCollection, WithHeadings
{
    public function collection(): Collection
    {
        return Libro::withCount('ejemplares') // cuenta automáticamente
            ->get()
            ->map(function ($libro) {
                return [
                    'Título' => $libro->titulo,
                    'Autor' => $libro->autor->nombre_completo ?? 'N/A',
                    'Editorial' => $libro->editorial->nombre_editorial ?? 'N/A',
                    'Año' => $libro->anio_publicacion ?? 'N/A',
                    'Categorías' => $libro->categoria->nombre_categoria ?? 'N/A',
                    'Cantidad de Ejemplares' => (string) $libro->ejemplares_count,
                ];
            });
    }

    public function headings(): array
    {
        return ['Título', 'Autor', 'Editorial', 'Año', 'Categorías', 'Cantidad de Ejemplares'];
    }
    /* public function styles(Worksheet $sheet)
    {
        // 1. Estilo para el encabezado (A1:F1)
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1F4E78'], // Azul oscuro
            ],
        ]);

        // 2. Estilo para el cuerpo completo (A2 hasta última fila dinámica)
        $highestRow = $sheet->getHighestRow(); // detecta la última fila con contenido

        $sheet->getStyle("A2:F$highestRow")->applyFromArray([
            'font' => ['name' => 'Calibri', 'size' => 11],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'CCCCCC'],
                ],
            ],
        ]);

        return [];
    } */
}
