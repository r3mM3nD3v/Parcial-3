<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiVideoController extends Controller
{
    /**
     * Mostrar la vista con las APIs de navegador.
     */
    public function index()
    {
        return view('apiVideo');
    }
}
