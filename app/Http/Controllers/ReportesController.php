<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\LibrosMasPrestadosExport;
use App\Exports\PrestamosPorFechaExport;
use App\Exports\PrestamosPorLector;
use App\Models\Estudiante;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

use PhpOffice\PhpSpreadsheet\Reader\Xls\RC4;

class ReportesController extends Controller
{
    public function index()
    {
        $students = Estudiante::all();
        // Aquí podrías enviar datos dinámicos si es necesario
        return view('administracion.reportes', compact('students'));
    }

    public function librosMasPrestados(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        return Excel::download(new LibrosMasPrestadosExport($fechaInicio, $fechaFin), 'libros_mas_prestados.xlsx');
    }

    public function prestamosPorFecha(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        return Excel::download(
            new PrestamosPorFechaExport($request->fecha_inicio, $request->fecha_fin),
            'prestamos_' . $request->fecha_inicio . '_a_' . $request->fecha_fin . '.xlsx'
        );
    }

    public function prestamosPorLector(Request $request)
    {
        $request->validate([
            'studentRUT' => 'required',
        ]);
        // Validar el RUT usando Laragear\Rut
        $rut = \Laragear\Rut\Rut::parse($request->studentRUT);
        if (!$rut->isValid()) {
            /* $this->showValidationAlert('El RUT ingresado no es válido.'); */
            return;
        }
        // Normalizar el RUT (formato con puntos y guion)
        $studentRUT = $rut->format();

        Log::debug("Se ejectuta la función prestamosPorLector con el RUT: " . $studentRUT);

        return Excel::download(
            new PrestamosPorLector($studentRUT),
            'prestamos_' . $studentRUT . '.xlsx'
        );
    }
}
