<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;
use Illuminate\Support\Facades\Log;

class LibrosController extends Controller
{
    public function index()
    {
        return view('administracion.libros');
    }
}
