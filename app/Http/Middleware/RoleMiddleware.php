<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $u = session('user');

        // Si no hay sesión, afuera
        if (!$u) {
            return redirect()->route('login');
        }

        $rolName = strtoupper(trim($u['rol'] ?? $u['nombre_rol'] ?? ''));
        $rolId   = (int)($u['id_rol'] ?? 0);

        // Mapa de IDs a nombres “oficiales” (según tu BD)
        $idToName = [
            1 => 'ADMIN',
            2 => 'RIFA',
            3 => 'GESTOR', // o DONACIONES, como prefieras manejarlo
        ];

        $actual = $rolName ?: ($idToName[$rolId] ?? '');

        // Normalizamos los roles permitidos
        $allowed = array_map(fn($r) => strtoupper(trim($r)), $roles);

        // Alias útiles (porque en tu proyecto a veces viene DONACIONES/GESTOR)
        $aliases = [
            'GESTOR'     => ['GESTOR', 'DONACIONES'],
            'DONACIONES' => ['GESTOR', 'DONACIONES'],
        ];

        $ok = false;

        foreach ($allowed as $r) {
            $set = $aliases[$r] ?? [$r];
            if (in_array($actual, $set, true)) {
                $ok = true;
                break;
            }
        }

        if (!$ok) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
