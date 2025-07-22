<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PrestamoService
{
    public static function verificarRetrasados(): void
    {
        $hoy = Carbon::today()->toDateString();

        if (session('prestamos_actualizados_hoy') !== $hoy) {
            DB::table('prestamos')
                ->where('estado', 'activo')
                ->whereDate('fecha_devolucion_esperada', '<', Carbon::today())
                ->update(['estado' => 'retrasado']);

            session()->put('prestamos_actualizados_hoy', $hoy);
            Log::debug("Prestamos actualizados hoy");
            Log::debug(session('prestamos_actualizados_hoy'));
        } else {
            Log::debug("Los préstamos ya han sido actualizados hoy");
        }
    }
}
