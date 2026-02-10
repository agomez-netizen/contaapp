<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contacto;
use App\Models\Proyecto;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ContactosExport;


class ContactoController extends Controller
{
    private function requireLogin(): void
    {
        if (!session()->has('user')) {
            abort(403, 'No hay usuario en sesión');
        }
    }

    public function index(Request $request)
    {
        $this->requireLogin();

        $q = trim((string) $request->get('q', ''));
        $proyecto = (int) $request->get('proyecto', 0);
        $tipo = trim((string) $request->get('tipo', ''));

        $contactos = Contacto::query()
            ->with('proyecto')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('nombre', 'like', "%{$q}%")
                       ->orWhere('correo', 'like', "%{$q}%")
                       ->orWhere('telefono', 'like', "%{$q}%")
                       ->orWhere('nit', 'like', "%{$q}%")
                       ->orWhere('motivo', 'like', "%{$q}%");
                });
            })
            ->when($proyecto > 0, fn($query) => $query->where('id_proyecto', $proyecto))
            ->when($tipo !== '', fn($query) => $query->where('tipo', $tipo))
            ->orderBy('id_contacto', 'desc')
            ->paginate(12)
            ->withQueryString();

        $proyectos = Proyecto::orderBy('nombre')->get();

        return view('contactos.index', compact('contactos', 'proyectos', 'q', 'proyecto', 'tipo'));
    }

    public function create()
    {
        $this->requireLogin();

        $proyectos = Proyecto::orderBy('nombre')->get();
        $tipos = ['Empresa', 'Fundacion', 'Persona', 'ONG'];

        return view('contactos.create', compact('proyectos', 'tipos'));
    }

    public function store(Request $request)
    {
        $this->requireLogin();

        $data = $request->validate([
            'id_proyecto' => ['required', 'integer', 'min:1'],
            'tipo'        => ['required', 'string', 'max:30'],
            'nombre'      => ['required', 'string', 'max:150'],
            'telefono'    => ['nullable', 'string', 'max:30'],
            'extension'   => ['nullable', 'string', 'max:10'],
            'correo'      => ['nullable', 'email', 'max:120'],
            'direccion'   => ['nullable', 'string', 'max:255'],
            'nit'         => ['nullable', 'string', 'max:30'],
            'motivo'      => ['nullable', 'string', 'max:255'],
        ]);

        Contacto::create($data);

        return redirect()->route('contactos.index')->with('success', 'Contacto creado correctamente.');
    }

    public function show($id)
    {
        $this->requireLogin();

        $contacto = Contacto::with('proyecto')->findOrFail($id);
        return view('contactos.show', compact('contacto'));
    }

    public function edit($id)
    {
        $this->requireLogin();

        $contacto = Contacto::findOrFail($id);
        $proyectos = Proyecto::orderBy('nombre')->get();
        $tipos = ['Empresa', 'Fundacion', 'Persona', 'ONG'];

        return view('contactos.edit', compact('contacto', 'proyectos', 'tipos'));
    }

    public function update(Request $request, $id)
    {
        $this->requireLogin();

        $contacto = Contacto::findOrFail($id);

        $data = $request->validate([
            'id_proyecto' => ['required', 'integer', 'min:1'],
            'tipo'        => ['required', 'string', 'max:30'],
            'nombre'      => ['required', 'string', 'max:150'],
            'telefono'    => ['nullable', 'string', 'max:30'],
            'extension'   => ['nullable', 'string', 'max:10'],
            'correo'      => ['nullable', 'email', 'max:120'],
            'direccion'   => ['nullable', 'string', 'max:255'],
            'nit'         => ['nullable', 'string', 'max:30'],
            'motivo'      => ['nullable', 'string', 'max:255'],
        ]);

        $contacto->update($data);

        return redirect()->route('contactos.show', $contacto->id_contacto)
            ->with('success', 'Contacto actualizado correctamente.');
    }

    public function destroy($id)
    {
        $this->requireLogin();

        $contacto = Contacto::findOrFail($id);
        $contacto->delete();

        return redirect()->route('contactos.index')->with('success', 'Contacto eliminado.');
    }


private function buildExportQueryAll(Request $request)
{
    $q        = trim((string) $request->get('q', ''));
    $proyecto = (int) $request->get('proyecto', 0);
    $tipo     = trim((string) $request->get('tipo', ''));

    return \Illuminate\Support\Facades\DB::table('contactos as c')
        ->join('proyectos as p', 'p.id_proyecto', '=', 'c.id_proyecto')
        ->select([
            'p.nombre as PROYECTO',
            'c.tipo as TIPO',
            'c.nombre as NOMBRE',
            'c.telefono as TELEFONO',
            'c.extension as EXTENCION',
            'c.correo as CORREO',
            'c.direccion as DIRECCION',
            'c.nit as NIT',
            'c.motivo as MOTIVO',
        ])
        ->when($q !== '', function ($query) use ($q) {
            $query->where(function ($qq) use ($q) {
                $qq->where('c.nombre', 'like', "%{$q}%")
                   ->orWhere('c.correo', 'like', "%{$q}%")
                   ->orWhere('c.telefono', 'like', "%{$q}%")
                   ->orWhere('c.nit', 'like', "%{$q}%")
                   ->orWhere('c.motivo', 'like', "%{$q}%");
            });
        })
        ->when($proyecto > 0, fn($query) => $query->where('c.id_proyecto', $proyecto))
        ->when($tipo !== '', fn($query) => $query->where('c.tipo', $tipo))
        ->orderByDesc('c.id_contacto');
}


public function exportExcel(Request $request)
{
    $rows = $this->buildExportQueryAll($request)->get();

    if ($rows->isEmpty()) {
        return back()->with('error', 'No hay datos para exportar.');
    }

    $headers = array_keys((array) $rows->first());

    $exportData = [];
    $exportData[] = $headers;

    foreach ($rows as $row) {
        $exportData[] = array_values((array) $row);
    }

    $fileName = 'contactos_' . now()->format('Ymd_His') . '.xlsx';

    return Excel::download(
        new \App\Exports\ArrayExport($exportData),
        $fileName
    );
}




}
