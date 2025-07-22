<?php

namespace App\Exports;

use App\Models\Libro;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LibrosMasPrestadosExport extends BaseStyledExport implements FromCollection, WithHeadings
{
    protected $fechaInicio;
    protected $fechaFin;

    public function __construct($fechaInicio, $fechaFin)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }

    public function collection(): Collection
    {
        return Libro::with(['autor', 'editorial'])
            ->withCount(['prestamos as total_prestamos' => function ($query) {
                $query->whereBetween('fecha_prestamo', [$this->fechaInicio, $this->fechaFin]);
            }])
            ->orderByDesc('total_prestamos')
            ->get()
            ->map(function ($libro) {
                return [
                    'Título' => $libro->titulo,
                    'Autor' => $libro->autor->nombre_completo ?? 'N/A',
                    'Editorial' => $libro->editorial->nombre_editorial ?? 'N/A',
                    'Veces Prestado' => (string) $libro->total_prestamos, // cast a string para que se exporte bien
                ];
            });
    }

    public function headings(): array
    {
        return ['Título', 'Autor', 'Editorial', 'Veces Prestado'];
    }
}
