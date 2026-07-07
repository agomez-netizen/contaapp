<?php

namespace App\Http\Controllers;

use App\Models\Organizacion;
use App\Models\ContactoOrganizacion;
use App\Models\TelefonoOrganizacion;
use App\Models\WebOrganizacion;
use App\Models\RedOrganizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArrayExport;

class CooperantesController extends Controller
{
    public function index()
    {
        $organizaciones = Organizacion::orderBy('id', 'desc')->paginate(15);

        return view('cooperantes.index', compact('organizaciones'));
    }

    public function create()
    {
        return view('cooperantes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
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

            if ($request->has('contactos')) {
                foreach ($request->contactos as $contacto) {
                    if (!empty($contacto['nombre'])) {
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
            }

            if ($request->has('telefonos')) {
                foreach ($request->telefonos as $telefono) {
                    if (!empty($telefono['numero'])) {
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
            }

            if ($request->has('webs')) {
                foreach ($request->webs as $web) {
                    if (!empty($web['url'])) {
                        WebOrganizacion::create([
                            'organizacion_id' => $organizacion->id,
                            'tipo' => $web['tipo'] ?? 'Sitio web',
                            'url' => $web['url'],
                            'descripcion' => $web['descripcion'] ?? null,
                            'activo' => isset($web['activo']) ? 1 : 0,
                        ]);
                    }
                }
            }

            if ($request->has('redes')) {
                foreach ($request->redes as $red) {
                    if (!empty($red['url']) || !empty($red['usuario'])) {
                        RedOrganizacion::create([
                            'organizacion_id' => $organizacion->id,
                            'red_social' => $red['red_social'] ?? null,
                            'url' => $red['url'] ?? null,
                            'usuario' => $red['usuario'] ?? null,
                            'notas' => $red['notas'] ?? null,
                        ]);
                    }
                }
            }
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
            'redes'
        ])->findOrFail($id);

        return view('cooperantes.show', compact('organizacion'));
    }

    public function edit($id)
    {
        $organizacion = Organizacion::with([
            'contactos',
            'telefonos',
            'webs',
            'redes'
        ])->findOrFail($id);

        return view('cooperantes.edit', compact('organizacion'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
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

            $organizacion->contactos()->delete();
            $organizacion->telefonos()->delete();
            $organizacion->webs()->delete();
            $organizacion->redes()->delete();

            if ($request->has('contactos')) {
                foreach ($request->contactos as $contacto) {
                    if (!empty($contacto['nombre'])) {
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
            }

            if ($request->has('telefonos')) {
                foreach ($request->telefonos as $telefono) {
                    if (!empty($telefono['numero'])) {
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
            }

            if ($request->has('webs')) {
                foreach ($request->webs as $web) {
                    if (!empty($web['url'])) {
                        WebOrganizacion::create([
                            'organizacion_id' => $organizacion->id,
                            'tipo' => $web['tipo'] ?? 'Sitio web',
                            'url' => $web['url'],
                            'descripcion' => $web['descripcion'] ?? null,
                            'activo' => isset($web['activo']) ? 1 : 0,
                        ]);
                    }
                }
            }

            if ($request->has('redes')) {
                foreach ($request->redes as $red) {
                    if (!empty($red['url']) || !empty($red['usuario'])) {
                        RedOrganizacion::create([
                            'organizacion_id' => $organizacion->id,
                            'red_social' => $red['red_social'] ?? null,
                            'url' => $red['url'] ?? null,
                            'usuario' => $red['usuario'] ?? null,
                            'notas' => $red['notas'] ?? null,
                        ]);
                    }
                }
            }
        });

        return redirect()
            ->route('cooperantes.index')
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

    public function exportarExcel()
{
    $organizaciones = Organizacion::with([
        'contactos',
        'telefonos',
        'webs',
        'redes'
    ])->orderBy('id', 'desc')->get();

    $data = [];

    $data[] = [
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
        'Contactos',
        'Teléfonos',
        'Sitios web',
        'Redes sociales',
        'Descripción'
    ];

    foreach ($organizaciones as $org) {

        $contactos = $org->contactos->map(function ($c) {
            return trim($c->nombre . ' | ' . $c->cargo . ' | ' . $c->correo . ' | ' . $c->whatsapp);
        })->implode("\n");

        $telefonos = $org->telefonos->map(function ($t) {
            return trim($t->tipo . ' | ' . $t->numero . ' | Ext: ' . $t->extension);
        })->implode("\n");

        $webs = $org->webs->map(function ($w) {
            return trim($w->tipo . ' | ' . $w->url);
        })->implode("\n");

        $redes = $org->redes->map(function ($r) {
            return trim($r->red_social . ' | ' . $r->usuario . ' | ' . $r->url);
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
            $contactos,
            $telefonos,
            $webs,
            $redes,
            $org->descripcion
        ];
    }

    return Excel::download(
        new ArrayExport($data),
        'directorio_cooperantes_aapos.xlsx'
    );
}
}
