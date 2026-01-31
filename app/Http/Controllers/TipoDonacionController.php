<?php

namespace App\Http\Controllers;

use App\Models\TipoDonacion;
use Illuminate\Http\Request;

class TipoDonacionController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));

        $tipos = TipoDonacion::query()
            ->when($q, fn($qq) => $qq->where('nombre', 'like', "%{$q}%"))
            ->orderByDesc('id_tipo_donacion')
            ->paginate(10)
            ->withQueryString();

        return view('tipos_donacion.index', compact('tipos', 'q'));
    }

    public function create()
    {
        return view('tipos_donacion.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required','string','max:120'],
            'descripcion' => ['nullable','string','max:2000'],
            'activo' => ['nullable','boolean'],
        ]);

        $data['activo'] = $request->boolean('activo');

        TipoDonacion::create($data);

        return redirect()->route('tipos_donacion.index')
            ->with('ok', 'Tipo de donación creado ✅');
    }

    public function show(TipoDonacion $tipos_donacion)
    {
        // Laravel usa el nombre del parámetro de ruta para el binding
        return view('tipos_donacion.show', ['tipo' => $tipos_donacion]);
    }

    public function edit(TipoDonacion $tipos_donacion)
    {
        return view('tipos_donacion.edit', ['tipo' => $tipos_donacion]);
    }

    public function update(Request $request, TipoDonacion $tipos_donacion)
    {
        $data = $request->validate([
            'nombre' => ['required','string','max:120'],
            'descripcion' => ['nullable','string','max:2000'],
            'activo' => ['nullable','boolean'],
        ]);

        $data['activo'] = $request->boolean('activo');

        $tipos_donacion->update($data);

        return redirect()->route('tipos_donacion.index')
            ->with('ok', 'Tipo de donación actualizado ✅');
    }

    public function destroy(TipoDonacion $tipos_donacion)
    {
        $tipos_donacion->delete();

        return redirect()->route('tipos_donacion.index')
            ->with('ok', 'Tipo de donación eliminado 🧹');
    }
}
