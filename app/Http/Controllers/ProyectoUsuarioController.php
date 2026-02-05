<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Proyecto;

class ProyectoUsuarioController extends Controller
{
    private function requireAdmin(): void
    {
        $u = session('user');
        $rolName = strtoupper(trim($u['rol'] ?? $u['nombre_rol'] ?? ''));
        $rolId   = (int)($u['id_rol'] ?? 0);

        $isAdmin = ($rolId === 1) || ($rolName === 'ADMIN');

        if (!$isAdmin) {
            abort(403, 'No autorizado');
        }
    }

    public function index(Request $request)
    {
        $this->requireAdmin();

        $q = trim((string) $request->get('q', ''));

        $usuarios = Usuario::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('nombre', 'like', "%{$q}%")
                    ->orWhere('apellido', 'like', "%{$q}%")
                    ->orWhere('usuario', 'like', "%{$q}%");
            })
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        return view('proyectos.asignaciones.proyectos_usuarios.index', compact('usuarios', 'q'));

    }

    public function edit(int $id_usuario)
    {
        $this->requireAdmin();

        $usuario = Usuario::findOrFail($id_usuario);

        $proyectos = Proyecto::where('activo', 1)
            ->orderBy('nombre')
            ->get(['id_proyecto', 'nombre']);

        // IDs ya asignados (usa la relación belongsToMany)
        $asignados = $usuario->proyectos()->pluck('proyectos.id_proyecto')->toArray();

        return view('proyectos.asignaciones.proyectos_usuarios.edit', compact('usuario', 'proyectos', 'asignados'));

    }

    public function update(Request $request, int $id_usuario)
    {
        $this->requireAdmin();

        $usuario = Usuario::findOrFail($id_usuario);

        $data = $request->validate([
            'proyectos'   => ['nullable', 'array'],
            'proyectos.*' => ['integer'],
        ]);

        $ids = $data['proyectos'] ?? [];

        // Seguridad: solo proyectos activos existentes
        $idsValidos = Proyecto::where('activo', 1)
            ->whereIn('id_proyecto', $ids)
            ->pluck('id_proyecto')
            ->toArray();

        // Sync: deja exactamente los seleccionados
        $usuario->proyectos()->sync($idsValidos);

        return redirect()
            ->route('asignaciones.proyectos_usuarios.edit', $usuario->id_usuario)
            ->with('success', 'Asignaciones actualizadas.');
    }
}
