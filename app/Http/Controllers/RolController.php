<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));

        $roles = Rol::query()
            ->withCount('usuarios')
            ->when($q, fn($qq) => $qq->where('nombre', 'like', "%{$q}%"))
            ->orderByDesc('id_rol')
            ->paginate(10)
            ->withQueryString();

        return view('roles.index', compact('roles', 'q'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required','string','max:120','unique:roles,nombre'],
            'descripcion' => ['nullable','string','max:2000'],
        ]);

        Rol::create($data);

        return redirect()->route('roles.index')->with('ok', 'Rol creado ✅');
    }

    public function show($role)
    {
        $rol = Rol::where('id_rol', $role)->firstOrFail();
        $rol->loadCount('usuarios');

        return view('roles.show', compact('rol'));
    }


   public function edit($role)
{
    $rol = Rol::where('id_rol', $role)->firstOrFail();
    return view('roles.edit', compact('rol'));
}

public function update(Request $request, $role)
{
    $rol = Rol::where('id_rol', $role)->firstOrFail();

    $data = $request->validate([
        'nombre' => ['required','string','max:120',"unique:roles,nombre,{$rol->id_rol},id_rol"],
        'descripcion' => ['nullable','string','max:2000'],
    ]);

    $rol->update($data);

    return redirect()->route('roles.index')->with('ok', 'Rol actualizado ✅');
}


    public function destroy($role)
    {
        $rol = Rol::where('id_rol', $role)->firstOrFail();

        // si hay usuarios con ese rol, no se elimina
        if ($rol->usuarios()->exists()) {
            return redirect()->route('roles.index')
                ->with('err', 'No se puede eliminar: hay usuarios usando este rol.');
        }

        $rol->delete();

        return redirect()->route('roles.index')->with('ok', 'Rol eliminado 🧹');
    }

}
