<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArrayExport;

class PacientesController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $pacientes = Paciente::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('nombre', 'like', "%{$q}%")
                    ->orWhere('dpi', 'like', "%{$q}%")
                    ->orWhere('telefono', 'like', "%{$q}%")
                    ->orWhere('departamento', 'like', "%{$q}%")
                    ->orWhere('municipio', 'like', "%{$q}%")
                    ->orWhere('prioridad', 'like', "%{$q}%")
                    ->orWhere('tipo_operacion', 'like', "%{$q}%")
                    ->orWhere('tipo_consulta', 'like', "%{$q}%");
            })
            ->orderByDesc('id_paciente')
            ->paginate(5)
            ->withQueryString();

        return view('pacientes.index', compact('pacientes', 'q'));
    }

    public function create()
    {
        return view('pacientes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'nullable|string|max:150',
            'dpi' => 'nullable|string|max:20|unique:pacientes,dpi',
            'sexo' => 'nullable|in:MASCULINO,FEMENINO',
            'edad' => 'nullable|integer|min:0|max:120',

            'prioridad' => 'nullable|in:NORMAL,PRIORITARIO',
            'carnet' => 'nullable|string|max:50',

            'telefono' => 'nullable|string|max:25',
            'correo' => 'nullable|email|max:150',

            'departamento' => 'nullable|string|max:80',
            'municipio' => 'nullable|string|max:80',

            'tipo_consulta' => 'nullable|in:CONSULTA GENERAL,CONSULTA ESPECIALIZADA',
            'tipo_operacion' => 'nullable|string|max:120',

            'empresa' => 'nullable|in:EMPRESA,MUNICIPALIDAD,REFIRIENTE',
            'nombre_empresa' => 'nullable|string|max:150',

            'referido_por' => 'nullable|string|max:150',
            'telefono_referente' => 'nullable|string|max:25',
            'tipo_contacto' => 'nullable|in:Call Center,Celular Personal,Redes Sociales,Referencia Personal',
            'tipo_consulta_referente' => 'nullable|in:CONSULTA GENERAL,CONSULTA ESPECIALIZADA',

            'descripcion' => 'nullable|string',
        ]);

        $data['prioridad'] = $data['prioridad'] ?? 'NORMAL';

        Paciente::create($data);

        return redirect()
            ->route('pacientes.create')
            ->with('ok', '✅ Paciente guardado correctamente');
    }

    public function destroy($id)
    {
        $paciente = Paciente::findOrFail($id);
        $paciente->delete();

        return redirect()
            ->route('pacientes.index')
            ->with('ok', '🗑️ Paciente eliminado');
    }

    public function edit($id)
    {
        $paciente = Paciente::findOrFail($id);
        return view('pacientes.edit', compact('paciente'));
    }

    public function update(Request $request, $id)
    {
        $paciente = Paciente::findOrFail($id);

        $data = $request->validate([
            'nombre' => 'nullable|string|max:150',
            'dpi' => 'nullable|string|max:20|unique:pacientes,dpi,' . $paciente->id_paciente . ',id_paciente',
            'sexo' => 'nullable|in:MASCULINO,FEMENINO',
            'edad' => 'nullable|integer|min:0|max:120',

            'prioridad' => 'nullable|in:NORMAL,PRIORITARIO',
            'carnet' => 'nullable|string|max:50',

            'telefono' => 'nullable|string|max:25',
            'correo' => 'nullable|email|max:150',

            'departamento' => 'nullable|string|max:80',
            'municipio' => 'nullable|string|max:80',

            'tipo_consulta' => 'nullable|in:CONSULTA GENERAL,CONSULTA ESPECIALIZADA',
            'tipo_operacion' => 'nullable|string|max:120',

            'empresa' => 'nullable|in:EMPRESA,MUNICIPALIDAD,REFIRIENTE',
            'nombre_empresa' => 'nullable|string|max:150',

            'referido_por' => 'nullable|string|max:150',
            'telefono_referente' => 'nullable|string|max:25',
            'tipo_contacto' => 'nullable|in:Call Center,Celular Personal,Redes Sociales,Referencia Personal',
            'tipo_consulta_referente' => 'nullable|in:CONSULTA GENERAL,CONSULTA ESPECIALIZADA',

            'descripcion' => 'nullable|string',
        ]);

        $data['prioridad'] = $data['prioridad'] ?? 'NORMAL';

        $paciente->update($data);

        return redirect()
            ->route('pacientes.index')
            ->with('ok', '✅ Paciente actualizado');
    }

    public function show($id)
    {
        $paciente = Paciente::where('id_paciente', $id)->firstOrFail();
        return view('pacientes.show', compact('paciente'));
    }

    public function exportExcel(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $items = Paciente::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('nombre', 'like', "%{$q}%")
                    ->orWhere('dpi', 'like', "%{$q}%")
                    ->orWhere('telefono', 'like', "%{$q}%")
                    ->orWhere('departamento', 'like', "%{$q}%")
                    ->orWhere('municipio', 'like', "%{$q}%")
                    ->orWhere('prioridad', 'like', "%{$q}%")
                    ->orWhere('tipo_operacion', 'like', "%{$q}%")
                    ->orWhere('tipo_consulta', 'like', "%{$q}%");
            })
            ->orderByDesc('id_paciente')
            ->get();

        $rows = [];
        $rows[] = ['ID', 'Nombre', 'Prioridad', 'DPI', 'Teléfono', 'Departamento', 'Municipio', 'Consulta', 'Tipo Operación'];

        foreach ($items as $p) {
            $rows[] = [
                (string) ($p->id_paciente ?? ''),
                (string) ($p->nombre ?? ''),
                (string) ($p->prioridad ?? 'NORMAL'),
                (string) ($p->dpi ?? ''),
                (string) ($p->telefono ?? ''),
                (string) ($p->departamento ?? ''),
                (string) ($p->municipio ?? ''),
                (string) ($p->tipo_consulta ?? ''),
                (string) ($p->tipo_operacion ?? ''),
            ];
        }

        return Excel::download(new ArrayExport($rows), 'pacientes.xlsx');
    }
}
