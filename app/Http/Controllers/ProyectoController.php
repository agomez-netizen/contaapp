<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArrayExport;

class ProyectoController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        // Cargamos encargados (usuarios asignados) para poder mostrarlos en el listado
        $proyectos = Proyecto::query()
            ->with(['usuarios' => function ($u) {
                // Traemos solo lo necesario (ajusta si tu tabla usa otros nombres)
                $u->select('usuarios.id_usuario', 'usuarios.nombre', 'usuarios.apellido');
            }])
            ->when($q !== '', fn($qq) => $qq->where('nombre', 'like', "%{$q}%"))
            ->orderByDesc('id_proyecto')
            ->paginate(10)
            ->withQueryString();

        return view('proyectos.index', compact('proyectos', 'q'));
    }

    public function exportExcel(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $items = Proyecto::query()
            ->with(['usuarios' => function ($u) {
                $u->select('usuarios.id_usuario', 'usuarios.nombre', 'usuarios.apellido');
            }])
            ->when($q !== '', fn($qq) => $qq->where('nombre', 'like', "%{$q}%"))
            ->orderByDesc('id_proyecto')
            ->get();

        $rows = [];
        $rows[] = ['Nombre', 'Activo', 'Encargados'];

        foreach ($items as $p) {
            $encargados = $p->usuarios
                ? $p->usuarios->map(function ($u) {
                    return trim(($u->nombre ?? '') . ' ' . ($u->apellido ?? ''));
                })->filter()->implode(', ')
                : '';

            $rows[] = [
                (string) ($p->nombre ?? ''),
                ($p->activo ? 'Sí' : 'No'),
                $encargados !== '' ? $encargados : '—',
            ];
        }

        return Excel::download(new ArrayExport($rows), 'proyectos.xlsx');
    }

    public function create()
    {
        return view('proyectos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'      => ['required', 'string', 'max:120'],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'direccion'   => ['nullable', 'string', 'max:255'],
            'activo'      => ['nullable', 'boolean'],
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
            'nombre'      => ['required', 'string', 'max:120'],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'direccion'   => ['nullable', 'string', 'max:255'],
            'activo'      => ['nullable', 'boolean'],
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

    public function exportExcelDescripcion(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $items = Proyecto::query()
            ->select(['id_proyecto', 'nombre', 'descripcion', 'direccion', 'activo'])
            ->when($q !== '', fn($qq) => $qq->where('nombre', 'like', "%{$q}%"))
            ->orderByDesc('id_proyecto')
            ->get();

        $rows = [];
        $rows[] = ['Nombre', 'Descripción', 'Dirección', 'Activo'];

        foreach ($items as $p) {
            $rows[] = [
                (string) ($p->nombre ?? ''),
                (string) ($p->descripcion ?? ''),
                (string) ($p->direccion ?? ''),
                ($p->activo ? 'Sí' : 'No'),
            ];
        }

        return Excel::download(new ArrayExport($rows), 'proyectos_descripcion.xlsx');
    }
}
