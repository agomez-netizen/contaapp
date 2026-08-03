<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Paciente;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArrayExport;

class PacientesController extends Controller
{
    private function reglasPaciente(?Paciente $paciente = null): array
    {
        $idPaciente = $paciente?->id_paciente;

        return [
            'nombre' => 'nullable|string|max:150',
            'dpi' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('pacientes', 'dpi')
                    ->ignore($idPaciente, 'id_paciente'),
            ],
            'sexo' => 'nullable|in:MASCULINO,FEMENINO',
            'edad' => 'nullable|integer|min:0|max:120',

            'prioridad' => 'nullable|in:NORMAL,PRIORITARIO',
            'estado_paciente' => 'nullable|in:EN ESPERA,EN JORNADA',
            'carnet' => 'nullable|string|max:50',

            'telefono' => 'nullable|string|max:25',
            'correo' => 'nullable|email|max:150',

            'departamento' => 'nullable|string|max:80',
            'municipio' => 'nullable|string|max:80',

            'tipo_consulta' => 'nullable|in:CONSULTA GENERAL,CONSULTA ESPECIALIZADA',
            'tipo_operacion' => 'nullable|string|max:120',

            'tipo_rebaja_tramite' => [
                'nullable',
                Rule::in([
                    'Rebaja en examen',
                    'Rebaja en operación',
                    'Trámite de examen en otras instituciones',
                    'Trámite de casa para quedarse un día antes de su operación',
                ]),
            ],

            'institucion_examen' => [
                'nullable',
                Rule::requiredIf(
                    fn () => request('tipo_rebaja_tramite') ===
                        'Trámite de examen en otras instituciones'
                ),
                Rule::in([
                    'Tesla',
                    'Tecniscan',
                    'Telerad',
                    'Clínicas San Nicolás',
                ]),
            ],

            'lugar_ingreso' => [
                'nullable',
                Rule::in([
                    'Renacer',
                    'Virgen del Socorro',
                    'Albergue Hermano Pedro',
                ]),
            ],

            'empresa' => 'nullable|in:EMPRESA,MUNICIPALIDAD,REFIRIENTE',
            'nombre_empresa' => 'nullable|string|max:150',

            'referido_por' => 'nullable|string|max:150',
            'telefono_referente' => 'nullable|string|max:25',
            'tipo_contacto' => 'nullable|in:Call Center,Celular Personal,Redes Sociales,Referencia Personal',
            'tipo_consulta_referente' => 'nullable|in:CONSULTA GENERAL,CONSULTA ESPECIALIZADA',

            'descripcion' => 'nullable|string',
        ];
    }

    private function prepararDatos(array $data): array
    {
        $data['prioridad'] = $data['prioridad'] ?? 'NORMAL';
        $data['estado_paciente'] = $data['estado_paciente'] ?? 'EN ESPERA';

        if (
            ($data['tipo_rebaja_tramite'] ?? null) !==
            'Trámite de examen en otras instituciones'
        ) {
            $data['institucion_examen'] = null;
        }

        return $data;
    }

    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $pacientes = Paciente::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($subquery) use ($q) {
                    $subquery->where('nombre', 'like', "%{$q}%")
                        ->orWhere('dpi', 'like', "%{$q}%")
                        ->orWhere('telefono', 'like', "%{$q}%")
                        ->orWhere('departamento', 'like', "%{$q}%")
                        ->orWhere('municipio', 'like', "%{$q}%")
                        ->orWhere('prioridad', 'like', "%{$q}%")
                        ->orWhere('estado_paciente', 'like', "%{$q}%")
                        ->orWhere('tipo_operacion', 'like', "%{$q}%")
                        ->orWhere('tipo_consulta', 'like', "%{$q}%")
                        ->orWhere('tipo_rebaja_tramite', 'like', "%{$q}%")
                        ->orWhere('institucion_examen', 'like', "%{$q}%")
                        ->orWhere('lugar_ingreso', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('id_paciente')
            ->paginate(25)
            ->withQueryString();

        return view('pacientes.index', compact('pacientes', 'q'));
    }

    public function create()
    {
        return view('pacientes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->reglasPaciente());
        $data = $this->prepararDatos($data);

        Paciente::create($data);

        return redirect()
            ->route('pacientes.create')
            ->with('ok', 'Paciente guardado correctamente');
    }

    public function destroy($id)
    {
        $paciente = Paciente::findOrFail($id);
        $paciente->delete();

        return redirect()
            ->route('pacientes.index')
            ->with('ok', 'Paciente eliminado');
    }

    public function edit($id)
    {
        $paciente = Paciente::findOrFail($id);

        return view('pacientes.edit', compact('paciente'));
    }

    public function update(Request $request, $id)
    {
        $paciente = Paciente::findOrFail($id);

        $data = $request->validate($this->reglasPaciente($paciente));
        $data = $this->prepararDatos($data);

        $paciente->update($data);

        return redirect()
            ->route('pacientes.index')
            ->with('ok', 'Paciente actualizado');
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
                $query->where(function ($subquery) use ($q) {
                    $subquery->where('nombre', 'like', "%{$q}%")
                        ->orWhere('dpi', 'like', "%{$q}%")
                        ->orWhere('telefono', 'like', "%{$q}%")
                        ->orWhere('departamento', 'like', "%{$q}%")
                        ->orWhere('municipio', 'like', "%{$q}%")
                        ->orWhere('prioridad', 'like', "%{$q}%")
                        ->orWhere('estado_paciente', 'like', "%{$q}%")
                        ->orWhere('tipo_operacion', 'like', "%{$q}%")
                        ->orWhere('tipo_consulta', 'like', "%{$q}%")
                        ->orWhere('tipo_rebaja_tramite', 'like', "%{$q}%")
                        ->orWhere('institucion_examen', 'like', "%{$q}%")
                        ->orWhere('lugar_ingreso', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('id_paciente')
            ->get();

        $rows = [];
        $rows[] = [
            'ID',
            'Nombre',
            'Prioridad',
            'Estado',
            'DPI',
            'Teléfono',
            'Departamento',
            'Municipio',
            'Consulta',
            'Tipo Operación',
            'Rebaja o Trámite',
            'Institución',
            'Lugar de Ingreso',
            'Descripción',
        ];

        foreach ($items as $p) {
            $rows[] = [
                (string) ($p->id_paciente ?? ''),
                (string) ($p->nombre ?? ''),
                (string) ($p->prioridad ?? 'NORMAL'),
                (string) ($p->estado_paciente ?? 'EN ESPERA'),
                (string) ($p->dpi ?? ''),
                (string) ($p->telefono ?? ''),
                (string) ($p->departamento ?? ''),
                (string) ($p->municipio ?? ''),
                (string) ($p->tipo_consulta ?? ''),
                (string) ($p->tipo_operacion ?? ''),
                (string) ($p->tipo_rebaja_tramite ?? ''),
                (string) ($p->institucion_examen ?? ''),
                (string) ($p->lugar_ingreso ?? ''),
                (string) ($p->descripcion ?? ''),
            ];
        }

        return Excel::download(new ArrayExport($rows), 'pacientes.xlsx');
    }
}
