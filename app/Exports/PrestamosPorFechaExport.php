<?php

namespace App\Exports;

use App\Models\Prestamo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PrestamosPorFechaExport extends BaseStyledExport implements FromCollection, WithHeadings
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
        return Prestamo::with(['ejemplar', 'estudiante']) // o 'estudiante' si ese es tu modelo
            ->whereBetween('fecha_prestamo', [$this->fechaInicio, $this->fechaFin])
            ->get()
            ->map(function ($prestamo) {
                return [
                    'Libro' => $prestamo->ejemplar->libro->titulo ?? 'N/A',
                    'Rut Estudiante' => $prestamo->rut_estudiante ?? 'N/A',
                    'Nombre completo' => $prestamo->estudiante->nombres . ' ' . $prestamo->estudiante->apellido_paterno . ' ' . $prestamo->estudiante->apellido_materno ?? 'N/A',
                    'Fecha Préstamo' => $prestamo->fecha_prestamo,
                    'Fecha Devolución' => $prestamo->fecha_devolucion_esperada,
                    'Estado' => $prestamo->estado,
                    'Observaciones' => $prestamo->observaciones
                ];
            });
    }

    public function headings(): array
    {
        return ['Libro', 'Rut Estudiante', 'Nombre completo', 'Fecha Préstamo', 'Fecha Devolución', 'Estado', "Observaciones"];
    }
}
