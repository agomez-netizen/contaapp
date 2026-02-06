<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Proyecto;

class ProyectoUsuarioController extends Controller
{
    private function requireRoles(array $allowedRoles = ['ADMIN', 'GESTOR', 'DONACIONES']): void
    {
        $u = session('user');

        if (!$u) {
            abort(403, 'No hay usuario en sesión');
        }

        $rolName = strtoupper(trim((string)($u['rol'] ?? $u['nombre_rol'] ?? '')));
        $rolId   = (int)($u['id_rol'] ?? 0);

        // Si manejas ID 1 como admin fijo
        if ($rolId === 1 || $rolName === 'ADMIN') {
            return;
        }

        // Normaliza permitidos
        $allowed = array_map(fn($r) => strtoupper(trim((string)$r)), $allowedRoles);

        // Alias (compatibilidad)
        $aliases = [
            'GESTOR'     => ['GESTOR', 'DONACIONES'],
            'DONACIONES' => ['GESTOR', 'DONACIONES'],
        ];

        $ok = false;
        foreach ($allowed as $r) {
            $set = $aliases[$r] ?? [$r];
            if (in_array($rolName, $set, true)) {
                $ok = true;
                break;
            }
        }

        if (!$ok) {
            abort(403, 'No autorizado');
        }
    }

    public function index(Request $request)
    {
        // Antes: requireAdmin()
        $this->requireRoles(['ADMIN', 'GESTOR', 'DONACIONES']);

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
        $this->requireRoles(['ADMIN', 'GESTOR', 'DONACIONES']);

        $usuario = Usuario::findOrFail($id_usuario);

        $proyectos = Proyecto::where('activo', 1)
            ->orderBy('nombre')
            ->get(['id_proyecto', 'nombre']);

        $asignados = $usuario->proyectos()->pluck('proyectos.id_proyecto')->toArray();

        return view('proyectos.asignaciones.proyectos_usuarios.edit', compact('usuario', 'proyectos', 'asignados'));
    }

    public function update(Request $request, int $id_usuario)
    {
        $this->requireRoles(['ADMIN', 'GESTOR', 'DONACIONES']);

        $usuario = Usuario::findOrFail($id_usuario);

        $data = $request->validate([
            'proyectos'   => ['nullable', 'array'],
            'proyectos.*' => ['integer'],
        ]);

        $ids = $data['proyectos'] ?? [];

        $idsValidos = Proyecto::where('activo', 1)
            ->whereIn('id_proyecto', $ids)
            ->pluck('id_proyecto')
            ->toArray();

        $usuario->proyectos()->sync($idsValidos);

        return redirect()
            ->route('asignaciones.proyectos_usuarios.edit', $usuario->id_usuario)
            ->with('success', 'Asignaciones actualizadas.');
    }
}
