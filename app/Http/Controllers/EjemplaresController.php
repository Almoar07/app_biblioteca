<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;
use App\Models\Ejemplar;
use Illuminate\Support\Facades\Log;


class EjemplaresController extends Controller
{
    //

    public function index()
    {
        // Obtener todos los ejemplares
        $ejemplares = Ejemplar::all();
        return view('administracion.ejemplares', compact('ejemplares'));
    }

    public function indexWithID($id_libro)
    {
        // Suponiendo que tienes un modelo Ejemplar relacionado con Libro
        // Obtener los ejemplares del libro con el ID proporcionado
        $ejemplares = Ejemplar::where('id_libro', $id_libro)->get();

        $libro = Libro::with(['autor', 'categoria', 'editorial'])->find($id_libro);

        return view('administracion.ejemplares', compact('ejemplares', 'libro'));
    }

    public function create()
    {

        // Mostrar el formulario para crear un nuevo ejemplar
        return view('administracion.ejemplares.create');
    }
}
