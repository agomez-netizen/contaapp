<?php

namespace App\Http\Controllers;

use App\Models\Subproyecto;
use App\Models\Proyecto;
use Illuminate\Http\Request;

class SubproyectoController extends Controller
{
    public function index()
    {
        $subproyectos = Subproyecto::with('proyecto')
            ->orderBy('nombre', 'asc')
            ->get();

        $proyectos = Proyecto::where('activo', 1)
            ->orderBy('nombre', 'asc')
            ->get();

        return view('subproyectos.index', compact('subproyectos', 'proyectos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_proyecto' => 'required',
            'nombre' => 'required|max:150',
            'descripcion' => 'nullable|max:255',
        ]);

        Subproyecto::create([
            'id_proyecto' => $request->id_proyecto,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'activo' => $request->activo ?? 1,
        ]);

        return redirect()
            ->route('subproyectos.index')
            ->with('success', 'Subproyecto guardado correctamente.');
    }

    public function porProyecto($id)
    {
        $subproyectos = Subproyecto::where('id_proyecto', $id)
            ->where('activo', 1)
            ->orderBy('nombre', 'asc')
            ->get();

        return response()->json($subproyectos);
}
}
