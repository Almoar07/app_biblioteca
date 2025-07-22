<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;

use Illuminate\Http\Request;

class PrestamosController extends Controller
{
    //
    public function index()
    {
        return view('administracion.prestamos');
    }
    public function show($id)
    {
        $prestamo = Prestamo::findOrFail($id);

        return view('administracion.prestamos', compact('prestamo'));
    }
}
