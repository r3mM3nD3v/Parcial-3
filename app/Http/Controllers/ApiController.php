<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function index()
    {
        // Carga resources/views/api.blade.php
        return view('apis');
    }
}
