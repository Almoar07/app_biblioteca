<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Ejemplar;
use Carbon\Carbon;

class PrestamosSeeder extends Seeder
{
    public function run(): void
    {
        $bibliotecarioId = 2; // Pepe
        $estudianteRut = '19.849.608-1';
        $ejemplares = Ejemplar::orderBy('id_ejemplar')->take(20)->get();

        // Distribución por mes: abril (4), mayo (6), junio (9), julio (1)
        $meses = [
            0 => '2025-04-10',
            1 => '2025-04-15',
            2 => '2025-04-20',
            3 => '2025-04-25',
            4 => '2025-05-02',
            5 => '2025-05-07',
            6 => '2025-05-12',
            7 => '2025-05-17',
            8 => '2025-05-22',
            9 => '2025-05-27',
            10 => '2025-06-01',
            11 => '2025-06-05',
            12 => '2025-06-10',
            13 => '2025-06-15',
            14 => '2025-06-20',
            15 => '2025-06-25',
            16 => '2025-06-29',
            17 => '2025-07-01',
            18 => '2025-07-02',
            19 => '2025-07-03',
        ];

        foreach ($ejemplares as $index => $ejemplar) {
            $fechaPrestamo = Carbon::parse($meses[$index]);
            $estado = match ($index % 4) {
                0 => 'activo',
                1 => 'retrasado',
                2 => 'devuelto_al_dia',
                3 => 'devuelto_con_retraso',
            };

            $fechaDevolucionEsperada = $fechaPrestamo->copy()->addDays(15);
            $fechaDevolucionReal = null;

            if (str_contains($estado, 'devuelto')) {
                $fechaDevolucionReal = match ($estado) {
                    'devuelto_al_dia' => $fechaDevolucionEsperada->copy(),
                    'devuelto_con_retraso' => $fechaDevolucionEsperada->copy()->addDays(3),
                };
                $ejemplar->status = 'disponible';
            } elseif ($estado === 'activo') {
                $fechaDevolucionEsperada = Carbon::now()->addDays(5);
                $ejemplar->status = 'prestado';
            } elseif ($estado === 'retrasado') {
                $fechaDevolucionEsperada = Carbon::now()->subDays(3);
                $ejemplar->status = 'prestado';
            }

            $ejemplar->save();

            DB::table('prestamos')->insert([
                'id_ejemplar' => $ejemplar->id_ejemplar,
                'rut_estudiante' => $estudianteRut,
                'id_bibliotecario' => $bibliotecarioId,
                'fecha_prestamo' => $fechaPrestamo,
                'fecha_devolucion_esperada' => $fechaDevolucionEsperada,
                'fecha_devolucion_real' => $fechaDevolucionReal,
                'estado' => $estado,
                'observaciones' => "Préstamo generado por seeder en estado '{$estado}' durante {$fechaPrestamo->format('F')}.",
                'created_by' => 'PrestamosSeeder',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
