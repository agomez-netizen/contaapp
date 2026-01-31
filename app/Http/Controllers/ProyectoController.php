<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;
use App\Http\Controllers\ProyectoController;


class ProyectoController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));

        $proyectos = Proyecto::query()
            ->when($q, fn($qq) => $qq->where('nombre', 'like', "%{$q}%"))
            ->orderByDesc('id_proyecto')
            ->paginate(10)
            ->withQueryString();

        return view('proyectos.index', compact('proyectos', 'q'));
    }

    public function create()
    {
        return view('proyectos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required','string','max:120'],
            'descripcion' => ['nullable','string','max:2000'],
            'activo' => ['nullable','boolean'],
        ]);

        $data['activo'] = $request->boolean('activo');

        Proyecto::create($data);

        return redirect()->route('proyectos.index')
            ->with('ok', 'Proyecto creado ✅');
    }

    public function show(Proyecto $proyecto)
    {
        return view('proyectos.show', compact('proyecto'));
    }

    public function edit(Proyecto $proyecto)
    {
        return view('proyectos.edit', compact('proyecto'));
    }

    public function update(Request $request, Proyecto $proyecto)
    {
        $data = $request->validate([
            'nombre' => ['required','string','max:120'],
            'descripcion' => ['nullable','string','max:2000'],
            'activo' => ['nullable','boolean'],
        ]);

        $data['activo'] = $request->boolean('activo');

        $proyecto->update($data);

        return redirect()->route('proyectos.index')
            ->with('ok', 'Proyecto actualizado ✅');
    }

    public function destroy(Proyecto $proyecto)
    {
        $proyecto->delete();

        return redirect()->route('proyectos.index')
            ->with('ok', 'Proyecto eliminado 🧹');
    }
}
