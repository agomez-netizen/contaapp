<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;

class PacientesController extends Controller
{

    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));

        $pacientes = Paciente::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('nombre', 'like', "%{$q}%")
                      ->orWhere('dpi', 'like', "%{$q}%")
                      ->orWhere('telefono', 'like', "%{$q}%")
                      ->orWhere('departamento', 'like', "%{$q}%")
                      ->orWhere('municipio', 'like', "%{$q}%");
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
        // ✅ VALIDACIÓN AQUÍ MISMO
        $request->validate([
            'nombre' => 'required|string|max:150',
            'dpi' => 'required|string|max:20|unique:pacientes,dpi',
            'sexo' => 'required|in:MASCULINO,FEMENINO',
            'edad' => 'required|integer|min:0|max:120',
            'carnet' => 'nullable|string|max:50',

            'telefono' => 'nullable|string|max:25',
            'correo' => 'nullable|email|max:150',

            'departamento' => 'required|string|max:80',
            'municipio' => 'required|string|max:80',

            'tipo_consulta' => 'required|in:CONSULTA GENERAL,CONSULTA ESPECIALIZADA',
            'empresa' => 'required|in:EMPRESA,MUNICIPALIDAD,REFIRIENTE',
            'nombre_empresa' => 'required|string|max:150',

            'referido_por' => 'nullable|string|max:150',
            'telefono_referente' => 'nullable|string|max:25',
            'tipo_contacto' => 'required|in:Call Center,Celular Personal,Redes Sociales,Referencia Personal',
            'tipo_consulta_referente' => 'nullable|in:CONSULTA GENERAL,CONSULTA ESPECIALIZADA',

            'descripcion' => 'nullable|string',
        ]);

        // ✅ GUARDAR
        Paciente::create($request->all());

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

        $request->validate([
            'nombre' => 'required|string|max:150',
            'dpi' => 'required|string|max:20|unique:pacientes,dpi,' . $paciente->id_paciente . ',id_paciente',
            'sexo' => 'required|in:MASCULINO,FEMENINO',
            'edad' => 'required|integer|min:0|max:120',
            'carnet' => 'nullable|string|max:50',

            'telefono' => 'nullable|string|max:25',
            'correo' => 'nullable|email|max:150',

            'departamento' => 'required|string|max:80',
            'municipio' => 'required|string|max:80',

            'tipo_consulta' => 'required|in:CONSULTA GENERAL,CONSULTA ESPECIALIZADA',
            'empresa' => 'required|in:EMPRESA,MUNICIPALIDAD,REFIRIENTE',
            'nombre_empresa' => 'required|string|max:150',

            'referido_por' => 'nullable|string|max:150',
            'telefono_referente' => 'nullable|string|max:25',
            'tipo_contacto' => 'required|in:Call Center,Celular Personal,Redes Sociales,Referencia Personal',
            'tipo_consulta_referente' => 'nullable|in:CONSULTA GENERAL,CONSULTA ESPECIALIZADA',

            'descripcion' => 'nullable|string',
        ]);

        $paciente->update($request->all());

            return redirect()->route('pacientes.index')->with('ok', '✅ Paciente actualizado');
        }


        public function show($id)
        {
            $paciente = \App\Models\Paciente::where('id_paciente', $id)->firstOrFail();
            return view('pacientes.show', compact('paciente'));
        }

        public function exportExcel(Request $request)
        {
            $q = trim($request->get('q', ''));

            $rows = \App\Models\Paciente::query()
                ->when($q !== '', function ($query) use ($q) {
                    $query->where('nombre', 'like', "%{$q}%")
                        ->orWhere('dpi', 'like', "%{$q}%")
                        ->orWhere('telefono', 'like', "%{$q}%")
                        ->orWhere('departamento', 'like', "%{$q}%")
                        ->orWhere('municipio', 'like', "%{$q}%");
                })
                ->orderByDesc('id_paciente')
                ->get();

            $filename = 'pacientes_' . date('Ymd_His') . '.csv';

            $headers = [
                "Content-Type" => "text/csv; charset=UTF-8",
                "Content-Disposition" => "attachment; filename={$filename}",
            ];

            $callback = function() use ($rows) {
                $out = fopen('php://output', 'w');

                // BOM para tildes en Excel
                fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));

                fputcsv($out, ['ID', 'Nombre', 'DPI', 'Teléfono', 'Departamento', 'Municipio', 'Consulta']);

                foreach ($rows as $p) {
                    fputcsv($out, [
                        $p->id_paciente,
                        $p->nombre,
                        $p->dpi,
                        $p->telefono,
                        $p->departamento,
                        $p->municipio,
                        $p->tipo_consulta ?? ($p->consulta ?? ''),
                    ]);
                }

                fclose($out);
            };

            return response()->stream($callback, 200, $headers);
        }

        


}
