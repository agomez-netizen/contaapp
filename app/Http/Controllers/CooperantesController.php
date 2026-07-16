<?php

namespace App\Http\Controllers;

use App\Models\Organizacion;
use App\Models\ContactoOrganizacion;
use App\Models\TelefonoOrganizacion;
use App\Models\WebOrganizacion;
use App\Models\RedOrganizacion;
use App\Models\Convocatoria;
use App\Models\ProyectoAapos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArrayExport;
use Barryvdh\DomPDF\Facade\Pdf;

class CooperantesController extends Controller
{
public function index(Request $request)
{
    $query = Organizacion::query();

    if ($request->filled('buscar')) {
        $query->where('nombre', 'LIKE', '%' . $request->buscar . '%');
    }

    if ($request->filled('prioridad')) {
        $query->where('prioridad', $request->prioridad);
    }

    if ($request->filled('estado')) {
        $query->where('estado', $request->estado);
    }

    $organizaciones = $query
        ->orderByDesc('id')
        ->paginate(15)
        ->withQueryString();

    $convocatoriasActivas = Convocatoria::where('estado', 'Activa')
        ->count();

    $aplicacionesEnviadas = Organizacion::where('estado', 'Aplicado')
        ->count();

    $cooperantesPorEstado = Organizacion::select(
            'estado',
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('estado')
        ->pluck('total', 'estado');

    $cooperantesPorPrioridad = Organizacion::select(
            'prioridad',
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('prioridad')
        ->pluck('total', 'prioridad');

    return view('cooperantes.index', compact(
        'organizaciones',
        'convocatoriasActivas',
        'aplicacionesEnviadas',
        'cooperantesPorEstado',
        'cooperantesPorPrioridad'
    ));
}

    public function create()
    {
        return view('cooperantes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'correo_general' => ['nullable', 'email', 'max:150'],
        ]);

        DB::transaction(function () use ($request) {
            $organizacion = Organizacion::create([
                'nombre' => $request->nombre,
                'tipo_organizacion' => $request->tipo_organizacion,
                'pais' => $request->pais,
                'direccion' => $request->direccion,
                'correo_general' => $request->correo_general,
                'area_apoyo' => $request->area_apoyo,
                'enfoque_geografico' => $request->enfoque_geografico,
                'idioma_comunicacion' => $request->idioma_comunicacion,
                'descripcion' => $request->descripcion,
                'monto_estimado' => $request->monto_estimado,
                'prioridad' => $request->prioridad ?? 'Media',
                'estado' => $request->estado ?? 'Identificada',
            ]);

            $this->guardarConvocatoria($request, $organizacion);
            $this->guardarContactos($request, $organizacion);
            $this->guardarTelefonos($request, $organizacion);
            $this->guardarWebs($request, $organizacion);
            $this->guardarRedes($request, $organizacion);
        });

        return redirect()
            ->route('cooperantes.index')
            ->with('success', 'Cooperante registrado correctamente.');
    }

    public function show($id)
    {
        $organizacion = Organizacion::with([
            'contactos',
            'telefonos',
            'webs',
            'redes',
            'convocatorias.proyectosOrganizacion.proyecto',
            'proyectosOrganizacion.proyecto',
            'proyectosOrganizacion.convocatoria',
            'seguimientos',
            'documentosRequeridos',
        ])->findOrFail($id);

        return view('cooperantes.show', compact('organizacion'));
    }

    public function exportarFichaPdf($id)
    {
        $organizacion = Organizacion::with([
            'contactos',
            'telefonos',
            'webs',
            'redes',
            'convocatorias.proyectosOrganizacion.proyecto',
            'proyectosOrganizacion.proyecto',
            'proyectosOrganizacion.convocatoria',
            'seguimientos',
            'documentosRequeridos',
        ])->findOrFail($id);

        $nombreArchivo = 'ficha_tecnica_' . str($organizacion->nombre)
            ->slug('_') . '.pdf';

        $pdf = Pdf::loadView(
            'cooperantes.ficha_pdf',
            compact('organizacion')
        )->setPaper('a4', 'portrait');

        return $pdf->download($nombreArchivo);
    }

    public function edit($id)
    {
        $organizacion = Organizacion::with([
            'contactos',
            'telefonos',
            'webs',
            'redes',
            'convocatorias.proyectosOrganizacion.proyecto',
            'proyectosOrganizacion.proyecto',
            'proyectosOrganizacion.convocatoria',
            'seguimientos',
            'documentosRequeridos',
        ])->findOrFail($id);

        $proyectosAapos = ProyectoAapos::orderBy('nombre')->get();

        return view('cooperantes.edit', compact(
            'organizacion',
            'proyectosAapos'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'correo_general' => ['nullable', 'email', 'max:150'],
        ]);

        DB::transaction(function () use ($request, $id) {
            $organizacion = Organizacion::findOrFail($id);

            $organizacion->update([
                'nombre' => $request->nombre,
                'tipo_organizacion' => $request->tipo_organizacion,
                'pais' => $request->pais,
                'direccion' => $request->direccion,
                'correo_general' => $request->correo_general,
                'area_apoyo' => $request->area_apoyo,
                'enfoque_geografico' => $request->enfoque_geografico,
                'idioma_comunicacion' => $request->idioma_comunicacion,
                'descripcion' => $request->descripcion,
                'monto_estimado' => $request->monto_estimado,
                'prioridad' => $request->prioridad ?? 'Media',
                'estado' => $request->estado ?? 'Identificada',
            ]);

            $this->guardarConvocatoria($request, $organizacion, true);

            $organizacion->contactos()->delete();
            $organizacion->telefonos()->delete();
            $organizacion->webs()->delete();
            $organizacion->redes()->delete();

            $this->guardarContactos($request, $organizacion);
            $this->guardarTelefonos($request, $organizacion);
            $this->guardarWebs($request, $organizacion);
            $this->guardarRedes($request, $organizacion);
        });

        return redirect()
            ->route('cooperantes.show', $id)
            ->with('success', 'Cooperante actualizado correctamente.');
    }

    public function destroy($id)
    {
        $organizacion = Organizacion::findOrFail($id);
        $organizacion->delete();

        return redirect()
            ->route('cooperantes.index')
            ->with('success', 'Cooperante eliminado correctamente.');
    }

    private function guardarConvocatoria(
        Request $request,
        Organizacion $organizacion,
        bool $esUpdate = false
    ): void {
        $conv = $request->input('convocatoria');

        if (!is_array($conv)) {
            return;
        }

        if (empty($conv['nombre']) && empty($conv['fecha_cierre'])) {
            return;
        }

        $data = [
            'nombre' => $conv['nombre'] ?? null,
            'tipo_apoyo' => $conv['tipo_apoyo'] ?? null,
            'fecha_apertura' => $conv['fecha_apertura'] ?: null,
            'fecha_cierre' => $conv['fecha_cierre'] ?: null,
            'monto_minimo' => $conv['monto_minimo'] ?: null,
            'monto_maximo' => $conv['monto_maximo'] ?: null,
            'moneda' => $conv['moneda'] ?? 'USD',
            'periodicidad' => $conv['periodicidad'] ?? null,
            'areas_prioritarias' => $conv['areas_prioritarias'] ?? null,
            'requisitos_clave' => $conv['requisitos_clave'] ?? null,
            'enlace' => $conv['enlace'] ?? null,
            'estado' => $conv['estado'] ?? 'Pendiente',
            'alerta_7_dias' => (int) ($conv['alerta_7_dias'] ?? 1),
            'correo_alerta' => $conv['correo_alerta'] ?? null,
        ];

        if ($esUpdate) {
            $data['alerta_enviada'] = 0;
            $data['fecha_alerta_enviada'] = null;

            $convocatoriaExistente = $organizacion->convocatorias()
                ->orderBy('id')
                ->first();

            if ($convocatoriaExistente) {
                $convocatoriaExistente->update($data);
            } else {
                $organizacion->convocatorias()->create($data);
            }

            return;
        }

        $organizacion->convocatorias()->create($data);
    }

    private function guardarContactos(
        Request $request,
        Organizacion $organizacion
    ): void {
        foreach ($request->input('contactos', []) as $contacto) {
            if (empty($contacto['nombre'])) {
                continue;
            }

            ContactoOrganizacion::create([
                'organizacion_id' => $organizacion->id,
                'nombre' => $contacto['nombre'],
                'cargo' => $contacto['cargo'] ?? null,
                'correo' => $contacto['correo'] ?? null,
                'telefono' => $contacto['telefono'] ?? null,
                'whatsapp' => $contacto['whatsapp'] ?? null,
                'idioma' => $contacto['idioma'] ?? null,
                'medio_preferido' => $contacto['medio_preferido'] ?? null,
                'notas' => $contacto['notas'] ?? null,
            ]);
        }
    }

    private function guardarTelefonos(
        Request $request,
        Organizacion $organizacion
    ): void {
        foreach ($request->input('telefonos', []) as $telefono) {
            if (empty($telefono['numero'])) {
                continue;
            }

            TelefonoOrganizacion::create([
                'organizacion_id' => $organizacion->id,
                'tipo' => $telefono['tipo'] ?? null,
                'numero' => $telefono['numero'],
                'extension' => $telefono['extension'] ?? null,
                'pais' => $telefono['pais'] ?? null,
                'observaciones' => $telefono['observaciones'] ?? null,
            ]);
        }
    }

    private function guardarWebs(
        Request $request,
        Organizacion $organizacion
    ): void {
        foreach ($request->input('webs', []) as $web) {
            if (empty($web['url'])) {
                continue;
            }

            WebOrganizacion::create([
                'organizacion_id' => $organizacion->id,
                'tipo' => $web['tipo'] ?? 'Sitio web',
                'url' => $web['url'],
                'descripcion' => $web['descripcion'] ?? null,
                'activo' => isset($web['activo']) ? 1 : 0,
            ]);
        }
    }

    private function guardarRedes(
        Request $request,
        Organizacion $organizacion
    ): void {
        foreach ($request->input('redes', []) as $red) {
            if (empty($red['url']) && empty($red['usuario'])) {
                continue;
            }

            RedOrganizacion::create([
                'organizacion_id' => $organizacion->id,
                'red_social' => $red['red_social'] ?? null,
                'url' => $red['url'] ?? null,
                'usuario' => $red['usuario'] ?? null,
                'notas' => $red['notas'] ?? null,
            ]);
        }
    }

    public function exportarExcel()
    {
        $organizaciones = Organizacion::with([
            'contactos',
            'telefonos',
            'webs',
            'redes',
            'convocatorias',
        ])->orderByDesc('id')->get();

        $data = [[
            'ID',
            'Organización',
            'Tipo',
            'País',
            'Correo general',
            'Área de apoyo',
            'Enfoque geográfico',
            'Idioma',
            'Monto estimado',
            'Prioridad',
            'Estado',
            'Convocatorias',
            'Contactos',
            'Teléfonos',
            'Sitios web',
            'Redes sociales',
            'Descripción',
        ]];

        foreach ($organizaciones as $org) {
            $convocatorias = $org->convocatorias->map(function ($c) {
                return trim(
                    $c->nombre
                    . ' | Cierre: ' . ($c->fecha_cierre ?? 'Sin fecha')
                    . ' | ' . $c->moneda . ' ' . ($c->monto_maximo ?? '')
                    . ' | Estado: ' . $c->estado
                );
            })->implode("\n");

            $contactos = $org->contactos->map(function ($c) {
                return trim(
                    $c->nombre . ' | '
                    . $c->cargo . ' | '
                    . $c->correo . ' | '
                    . $c->whatsapp
                );
            })->implode("\n");

            $telefonos = $org->telefonos->map(function ($t) {
                return trim(
                    $t->tipo . ' | '
                    . $t->numero . ' | Ext: '
                    . $t->extension
                );
            })->implode("\n");

            $webs = $org->webs->map(function ($w) {
                return trim($w->tipo . ' | ' . $w->url);
            })->implode("\n");

            $redes = $org->redes->map(function ($r) {
                return trim(
                    $r->red_social . ' | '
                    . $r->usuario . ' | '
                    . $r->url
                );
            })->implode("\n");

            $data[] = [
                $org->id,
                $org->nombre,
                $org->tipo_organizacion,
                $org->pais,
                $org->correo_general,
                $org->area_apoyo,
                $org->enfoque_geografico,
                $org->idioma_comunicacion,
                $org->monto_estimado,
                $org->prioridad,
                $org->estado,
                $convocatorias,
                $contactos,
                $telefonos,
                $webs,
                $redes,
                $org->descripcion,
            ];
        }

        return Excel::download(
            new ArrayExport($data),
            'directorio_cooperantes_aapos.xlsx'
        );
    }
}
