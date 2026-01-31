<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProyectosAAPOSController extends Controller
{
    public function index()
    {
        return view('proyectosaapos.index');
    }
}
