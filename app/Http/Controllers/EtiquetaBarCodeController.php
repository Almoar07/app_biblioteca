<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ejemplar;
use Barryvdh\DomPDF\Facade\Pdf;

class EtiquetaBarCodeController extends Controller
{
    public function generarPDF(Request $request)
    {
        $ids =  $request->query('ids', []);
        $ejemplares = Ejemplar::with('libro')->whereIn('id_ejemplar', $ids)->get();

        return Pdf::loadView('etiquetas.pdf', compact('ejemplares'))
            ->setPaper('letter')
            ->download('etiquetas.pdf');
    }
}
