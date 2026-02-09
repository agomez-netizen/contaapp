<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rubro;

class RubroController extends Controller
{
    private function requireLogin(): void
    {
        if (!session()->has('user')) {
            abort(403, 'No hay sesión activa');
        }
    }

    private function requireAdminOrGestor(): void
    {
        $this->requireLogin();

        $u = session('user');

        $rolName = strtoupper(trim($u['rol'] ?? $u['nombre_rol'] ?? ''));
        $rolId   = (int)($u['id_rol'] ?? 0);

        $isAdmin  = ($rolId === 1) || ($rolName === 'ADMIN');
        $isGestor = ($rolName === 'GESTOR');

        if (!$isAdmin && !$isGestor) {
            abort(403, 'No autorizado');
        }
    }

    public function index(Request $request)
    {
        $this->requireAdminOrGestor();

        $q = trim((string) $request->get('q', ''));
        $activo = $request->get('activo', ''); // '', 1, 0

        $rubros = Rubro::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('nombre', 'like', "%{$q}%");
            })
            ->when($activo !== '' && in_array((string)$activo, ['0','1'], true), function ($query) use ($activo) {
                $query->where('activo', (int)$activo);
            })
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        return view('rubros.index', compact('rubros', 'q', 'activo'));
    }

    public function create()
    {
        $this->requireAdminOrGestor();
        return view('rubros.create');
    }

    public function store(Request $request)
    {
        $this->requireAdminOrGestor();

        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:rubros,nombre'],
            'activo' => ['nullable', 'in:0,1'],
        ]);

        $data['activo'] = (int)($data['activo'] ?? 1);

        Rubro::create($data);

        return redirect()->route('rubros.index')
            ->with('success', 'Rubro creado correctamente.');
    }

    public function edit($id)
    {
        $this->requireAdminOrGestor();

        $rubro = Rubro::findOrFail($id);
        return view('rubros.edit', compact('rubro'));
    }

    public function update(Request $request, $id)
    {
        $this->requireAdminOrGestor();

        $rubro = Rubro::findOrFail($id);

        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:rubros,nombre,' . $rubro->id_rubro . ',id_rubro'],
            'activo' => ['nullable', 'in:0,1'],
        ]);

        $data['activo'] = (int)($data['activo'] ?? $rubro->activo);

        $rubro->update($data);

        return redirect()->route('rubros.index')
            ->with('success', 'Rubro actualizado correctamente.');
    }

    public function destroy($id)
    {
        $this->requireAdminOrGestor();

        $rubro = Rubro::findOrFail($id);
        $rubro->delete();

        return redirect()->route('rubros.index')
            ->with('success', 'Rubro eliminado.');
    }

    public function toggle($id)
    {
        $this->requireAdminOrGestor();

        $rubro = Rubro::findOrFail($id);
        $rubro->activo = !$rubro->activo;
        $rubro->save();

        return redirect()->route('rubros.index')
            ->with('success', 'Estado del rubro actualizado.');
    }
}
