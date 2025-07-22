<?php

namespace App\Http\Controllers;


use App\Models\Autor;
use Illuminate\Http\Request;

class AutoresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authors = Autor::all(); // Obtiene todos los autores

        return view('administracion.autores', compact('authors'));
    }
}
