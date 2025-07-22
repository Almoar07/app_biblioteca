<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EditorialesController extends Controller
{
    /**
     * Display the editorial management page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('administracion.editoriales');
    }
}
