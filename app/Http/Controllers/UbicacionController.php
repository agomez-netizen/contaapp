<?php

namespace App\Http\Controllers;

use App\Models\Ubicacion;
use Illuminate\Http\Request;

class UbicacionController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));
        $estado = $request->get('estado', ''); // '', '1', '0'

        $ubicaciones = Ubicacion::query()
            ->when($q, fn($query) => $query->where('nombre', 'like', "%{$q}%"))
            ->when($estado !== '' && in_array($estado, ['0','1'], true), fn($query) => $query->where('activo', $estado))
            ->orderBy('id_ubicacion', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('ubicaciones.index', compact('ubicaciones', 'q', 'estado'));
    }

    public function create()
    {
        return view('ubicaciones.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required','string','max:80','unique:ubicaciones,nombre'],
            'activo' => ['nullable','boolean'],
        ]);

        // checkbox no marcado => no viene en request
        $data['activo'] = $request->boolean('activo');

        Ubicacion::create($data);

        return redirect()
            ->route('ubicaciones.index')
            ->with('ok', 'Ubicación creada ✅');
    }

    public function show(Ubicacion $ubicacion)
    {
        return view('ubicaciones.show', compact('ubicacion'));
    }

    public function edit(Ubicacion $ubicacion)
    {
        return view('ubicaciones.edit', compact('ubicacion'));
    }


    public function update(Request $request, Ubicacion $ubicacion)
    {
        $data = $request->validate([
            'nombre' => ['required','string','max:80',"unique:ubicaciones,nombre,{$ubicacion->id_ubicacion},id_ubicacion"],
            'activo' => ['nullable','boolean'],
        ]);

        $data['activo'] = $request->boolean('activo');

        $ubicacion->update($data);

        return redirect()
            ->route('ubicaciones.index')
            ->with('ok', 'Ubicación actualizada ✨');
    }

    public function destroy(Ubicacion $ubicacion)
    {
        $ubicacion->delete();

        return redirect()
            ->route('ubicaciones.index')
            ->with('ok', 'Ubicación eliminada 🧹');
    }

    // Extra útil: toggle rápido (opcional)
    public function toggle(Ubicacion $ubicacion)
    {
        $ubicacion->activo = !$ubicacion->activo;
        $ubicacion->save();

        return back()->with('ok', 'Estado actualizado ⚡');
    }
}
