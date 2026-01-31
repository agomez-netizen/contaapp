<?php

namespace App\Http\Controllers;

use App\Models\Medio;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class MediosController extends Controller
{

    public function index(\Illuminate\Http\Request $request)
    {
        $q    = trim((string) $request->get('q'));
        $tipo = trim((string) $request->get('tipo'));

        $medios = \App\Models\Medio::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('medio', 'like', "%{$q}%")
                        ->orWhere('nombre', 'like', "%{$q}%")
                        ->orWhere('nombre_completo', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->when($tipo !== '', fn($query) => $query->where('tipo', $tipo))
            ->orderByDesc('id_medio')
            ->paginate(5)
            ->withQueryString();

        return view('medios.index', compact('medios'));
    }


    public function create()
    {
        return view('medios.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'medio' => 'required|string|max:150',
            'tipo'  => 'required|in:Local,Nacional,Internacional',
            'nombre' => 'required|string|max:150',
            'nombre_completo' => 'nullable|string|max:150',
            'telefono' => 'nullable|string|max:25',
            'contacto_cargo' => 'nullable|string|max:150',
            'celular_contacto' => 'nullable|string|max:25',
            'direccion' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:150',
            'website' => 'nullable|string|max:255',
            'redsocial' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        Medio::create($data);

        return redirect()->route('medios.index')->with('ok', 'Medio guardado ✅');
    }

    public function edit($id)
    {
        $medio = Medio::findOrFail($id);
        return view('medios.edit', compact('medio'));
    }

    public function update(Request $request, $id)
    {
        $medio = Medio::findOrFail($id);

        $data = $request->validate([
            'medio' => 'required|string|max:150',
            'tipo'  => 'required|in:Local,Nacional,Internacional',
            'nombre' => 'required|string|max:150',
            'nombre_completo' => 'nullable|string|max:150',
            'telefono' => 'nullable|string|max:25',
            'contacto_cargo' => 'nullable|string|max:150',
            'celular_contacto' => 'nullable|string|max:25',
            'direccion' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:150',
            'website' => 'nullable|string|max:255',
            'redsocial' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        $medio->update($data);

        return redirect()->route('medios.index')->with('ok', 'Medio actualizado ✅');
    }

    public function destroy($id)
    {
        $medio = Medio::findOrFail($id);
        $medio->delete();

        return redirect()->route('medios.index')->with('ok', 'Medio eliminado 🗑️');
    }


    public function show($id)
    {
        $medio = \App\Models\Medio::where('id_medio', $id)->firstOrFail();
        return view('medios.show', compact('medio'));
    }





public function exportExcel(Request $request)
{
    $q    = trim((string) $request->get('q', ''));
    $tipo = trim((string) $request->get('tipo', ''));

    $query = Medio::query()
        ->select([
            'id_medio',
            'medio',
            'tipo',
            'nombre',
            'nombre_completo',
            'telefono',
            'contacto_cargo',
            'celular_contacto',
            'direccion',
            'email',
            'website',
            'redsocial',
            'observaciones',
            'created_at',
            'updated_at',
        ])
        ->when($q !== '', function ($qq) use ($q) {
            $qq->where(function ($w) use ($q) {
                $w->where('medio', 'like', "%{$q}%")
                  ->orWhere('tipo', 'like', "%{$q}%")
                  ->orWhere('nombre', 'like', "%{$q}%")
                  ->orWhere('nombre_completo', 'like', "%{$q}%")
                  ->orWhere('telefono', 'like', "%{$q}%")
                  ->orWhere('contacto_cargo', 'like', "%{$q}%")
                  ->orWhere('celular_contacto', 'like', "%{$q}%")
                  ->orWhere('direccion', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
                  ->orWhere('website', 'like', "%{$q}%")
                  ->orWhere('redsocial', 'like', "%{$q}%")
                  ->orWhere('observaciones', 'like', "%{$q}%");
            });
        })
        ->when($tipo !== '', function ($qq) use ($tipo) {
            $qq->where('tipo', $tipo);
        })
        ->orderByDesc('id_medio');

    $rows = $query->get();

    if ($rows->isEmpty()) {
        return back()->with('error', 'No hay datos para exportar.');
    }

    // Encabezados "bonitos"
    $headers = [
        'ID',
        'Medio',
        'Tipo',
        'Nombre',
        'Nombre completo',
        'Teléfono',
        'Contacto/Cargo',
        'Celular contacto',
        'Dirección',
        'Email',
        'Website',
        'Red social',
        'Observaciones',
        'Creado',
        'Actualizado',
    ];

    $exportData = [];
    $exportData[] = $headers;

    foreach ($rows as $r) {
        $exportData[] = [
            $r->id_medio,
            $r->medio,
            $r->tipo,
            $r->nombre,
            $r->nombre_completo,
            $r->telefono,
            $r->contacto_cargo,
            $r->celular_contacto,
            $r->direccion,
            $r->email,
            $r->website,
            $r->redsocial,
            $r->observaciones,
            optional($r->created_at)->format('Y-m-d H:i:s'),
            optional($r->updated_at)->format('Y-m-d H:i:s'),
        ];
    }

    $fileName = 'medios_' . now()->format('Ymd_His') . '.xlsx';

    return Excel::download(
        new \App\Exports\ArrayExport($exportData),
        $fileName
    );
}


}
