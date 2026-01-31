<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));

        $usuarios = Usuario::query()
            ->with('rol')
            ->when($q, function ($qq) use ($q) {
                $qq->where('nombre', 'like', "%{$q}%")
                   ->orWhere('apellido', 'like', "%{$q}%")
                   ->orWhere('usuario', 'like', "%{$q}%");
            })
            ->orderByDesc('id_usuario')
            ->paginate(10)
            ->withQueryString();

        return view('usuarios.index', compact('usuarios', 'q'));
    }

    public function create()
    {
        $roles = Rol::orderBy('nombre')->get();
        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'   => ['required','string','max:120'],
            'apellido' => ['required','string','max:120'],
            'usuario'  => ['required','string','max:80','unique:usuarios,usuario'],
            'pass'     => ['required','string','min:4','max:255'],
            'id_rol'   => ['required','integer','exists:roles,id_rol'],
            'estado'   => ['required','in:1,0'],
        ]);

        // Si NO quieres hash, borra esta línea.
        $data['pass'] = bcrypt($data['pass']);

        Usuario::create($data);

        return redirect()->route('usuarios.index')
            ->with('ok', 'Usuario creado ✅');
    }

    public function show(Usuario $usuario)
    {
        $usuario->load('rol');
        return view('usuarios.show', compact('usuario'));
    }

    public function edit(Usuario $usuario)
    {
        $roles = Rol::orderBy('nombre')->get();
        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $data = $request->validate([
            'nombre'   => ['required','string','max:120'],
            'apellido' => ['required','string','max:120'],
            'usuario'  => ['required','string','max:80',"unique:usuarios,usuario,{$usuario->id_usuario},id_usuario"],
            'pass'     => ['nullable','string','min:4','max:255'],
            'id_rol'   => ['required','integer','exists:roles,id_rol'],
            'estado'   => ['required','in:1,0'],
        ]);

        // Si no escribió contraseña, no la tocamos
        if (!empty($data['pass'])) {
            $data['pass'] = bcrypt($data['pass']);
        } else {
            unset($data['pass']);
        }

        $usuario->update($data);

        return redirect()->route('usuarios.index')
            ->with('ok', 'Usuario actualizado ✅');
    }

    public function destroy(Usuario $usuario)
    {
        // Por seguridad: evita borrar el “admin principal” si quieres
        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('ok', 'Usuario eliminado 🧹');
    }
}
