<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

use App\Models\Prestamo;
use App\Models\Estudiante;

class PrestamosPorLector extends BaseStyledExport implements FromCollection, WithHeadings
{
    protected $studentRUT;


    public function __construct($studentRUT)
    {
        $this->studentRUT = $studentRUT;
    }

    public function collection(): Collection
    {
        Log::debug("Rut del estudiante: " . $this->studentRUT);
        return Prestamo::with(['ejemplar', 'estudiante']) // o 'estudiante' si ese es tu modelo
            ->where('rut_estudiante', $this->studentRUT)
            ->get()
            ->map(function ($prestamo) {
                return [
                    'RUT' => $prestamo->rut_estudiante,
                    'Nombre completo' => $prestamo->estudiante->nombres . ' ' . $prestamo->estudiante->apellido_paterno . ' ' . $prestamo->estudiante->apellido_materno,
                    'Curso' => $prestamo->estudiante->curso,
                    'Libro' => $prestamo->ejemplar->libro->titulo,
                    'Fecha Préstamo' => $prestamo->fecha_prestamo,
                    'Fecha Devolución' => $prestamo->fecha_devolucion_esperada,
                    'Estado de devolución' => $prestamo->estado,
                    'Observaciones' => $prestamo->observaciones
                ];
            });
    }

    public function headings(): array
    {
        return ['RUT', 'Nombre completo', 'Curso', 'Libro', 'Fecha Préstamo', 'Fecha Devolución', 'Estado de devolución', "Observaciones"];
    }
}
