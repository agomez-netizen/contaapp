<?php

namespace App\Http\Controllers;

use App\Models\Donacion;
use App\Models\Ubicacion;
use App\Models\TipoDonacion;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use App\Support\ReporteDonaciones;
use App\Support\DocumentosDonacion;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class DonacionController extends Controller
{
    public function create()
    {
        abort_unless($this->puedeModificarDonaciones(), 403);
        $ubicaciones = Ubicacion::where('activo', 1)->orderBy('nombre')->get();
        $tipos = TipoDonacion::where('activo', 1)->orderBy('nombre')->get();
        $proyectos = Proyecto::where('activo', 1)->orderBy('nombre')->get();

        return view('donaciones.create', compact('ubicaciones', 'tipos', 'proyectos'));
    }

    public function store(Request $request)
    {
        $u = session('user');
        if (!$u || !isset($u['id_usuario'])) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }

        abort_unless($this->puedeModificarDonaciones(), 403);
        if ((string) $request->input('recibo_empresa') === '1') {
            DocumentosDonacion::validar($request);
        }
        $data = $request->validate([
            'items' => ['required', 'array', 'min:1', 'max:100'],
            'items.*' => ['required', 'array'],
            'items.*.id_tipo_donacion' => ['required', 'integer', 'exists:tipos_donacion,id_tipo_donacion'],
            'items.*.id_proyecto' => ['required', 'integer', 'exists:proyectos,id_proyecto'],
            'items.*.descripcion' => ['nullable', 'string', 'max:5000'],
            'items.*.unidades' => ['nullable', 'integer', 'min:0', 'max:4294967295'],
            'items.*.monto' => ['required', 'numeric', 'min:0', 'max:9999999999.99', 'regex:/^\d{1,10}(\.\d{1,2})?$/'],
            'fecha_despachada' => ['nullable', 'date'],
            'empresa' => ['nullable', 'string', 'max:180'],
            'nit' => ['nullable', 'string', 'max:50'],
            'contacto' => ['nullable', 'string', 'max:120'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'correo' => ['nullable', 'email', 'max:150'],

            'descripcion' => ['nullable', 'string'],

            'id_ubicacion' => ['nullable', 'integer'],
            'fecha_recibe' => ['nullable', 'date'],
            'quien_recibe' => ['nullable', 'string', 'max:120'],
            'id_tipo_donacion' => ['nullable', 'integer'],
            'persona_gestiono' => ['nullable', 'string', 'max:120'],

            'costo_logistica' => ['nullable', 'numeric', 'min:0'],
            'descripcion_logistica' => ['nullable', 'string'],

            'impacto_personas' => ['nullable', 'integer', 'min:0'],
            'comentarios' => ['nullable', 'string'],

            'recibo_empresa' => ['nullable', 'in:0,1'],
            'ref_osshp' => ['exclude_unless:recibo_empresa,1', 'nullable', 'string', 'max:80'],
            'fecha_ref_osshp' => ['exclude_unless:recibo_empresa,1', 'nullable', 'date'],
            'ref_sat' => ['nullable', 'string', 'max:80'],
            'fecha_ref_sat' => ['exclude_unless:recibo_empresa,1', 'nullable', 'date'],
        ]);

        $data['id_usuario'] = $u['id_usuario'];

        // por defecto desbloqueado si la columna existe
        if (!array_key_exists('bloqueado', $data)) {
            $data['bloqueado'] = 0;
        }

        $data['recibo_empresa'] = (string) $request->input('recibo_empresa') === '1' ? 1 : 0;
        $items = $this->prepararItems($data);
        $guardados = [];
        try {
            DB::transaction(function () use ($data, $items, $request, &$guardados) {
                $donacion = Donacion::create($data);
                $donacion->detalles()->createMany($items);
                if ($data['recibo_empresa'] === 1) {
                    DocumentosDonacion::guardar($donacion->id_donacion, $request, $guardados);
                }
            });
        } catch (\Throwable $error) {
            DocumentosDonacion::limpiar($guardados);
            throw $error;
        }

        return redirect()
            ->route('donaciones.index')
            ->with('success', 'Donación guardada correctamente.');
    }

    public function edit($id)
    {

         if (!$this->puedeModificarDonaciones()) {
               abort(403, 'No tienes permiso para editar registros de Donaciones.');
         }

        $donacion = Donacion::where('id_donacion', $id)->firstOrFail();

        if ((int)($donacion->bloqueado ?? 0) === 1) {
            return back()->with('error', 'Este registro está bloqueado. No se puede modificar ni eliminar.');
        }

        $ubicaciones = Ubicacion::where('activo', 1)->orderBy('nombre')->get();
        $tipos = TipoDonacion::where('activo', 1)
            ->orWhereIn('id_tipo_donacion', $donacion->detalles()->pluck('id_tipo_donacion'))
            ->orWhere('id_tipo_donacion', $donacion->id_tipo_donacion)
            ->orderBy('nombre')->get();
        $proyectos = Proyecto::where('activo', 1)
            ->orWhereIn('id_proyecto', $donacion->detalles()->pluck('id_proyecto'))
            ->orWhere('id_proyecto', $donacion->id_proyecto)
            ->orderBy('nombre')->get();

        $documentos = DocumentosDonacion::listar($id);
        return view('donaciones.edit', compact('donacion', 'ubicaciones', 'tipos', 'proyectos', 'documentos'));
    }

    public function update(Request $request, $id)
    {

         if (!$this->puedeModificarDonaciones()) {
               abort(403, 'No tienes permiso para editar registros de Donaciones.');
         }

        $donacion = Donacion::where('id_donacion', $id)->firstOrFail();

        if ((int)($donacion->bloqueado ?? 0) === 1) {
            return back()->with('error', 'Este registro está bloqueado. No se puede modificar ni eliminar.');
        }

        if ((string) $request->input('recibo_empresa') === '1') {
            DocumentosDonacion::validar($request);
        }
        $data = $request->validate([
            'items' => ['required', 'array', 'min:1', 'max:100'],
            'items.*' => ['required', 'array'],
            'items.*.id_tipo_donacion' => ['required', 'integer', 'exists:tipos_donacion,id_tipo_donacion'],
            'items.*.id_proyecto' => ['required', 'integer', 'exists:proyectos,id_proyecto'],
            'items.*.descripcion' => ['nullable', 'string', 'max:5000'],
            'items.*.unidades' => ['nullable', 'integer', 'min:0', 'max:4294967295'],
            'items.*.monto' => ['required', 'numeric', 'min:0', 'max:9999999999.99', 'regex:/^\d{1,10}(\.\d{1,2})?$/'],
            'fecha_despachada' => ['nullable', 'date'],
            'empresa' => ['nullable', 'string', 'max:180'],
            'nit' => ['nullable', 'string', 'max:50'],
            'contacto' => ['nullable', 'string', 'max:120'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'correo' => ['nullable', 'email', 'max:150'],

            'descripcion' => ['nullable', 'string'],

            'id_ubicacion' => ['nullable', 'integer'],
            'fecha_recibe' => ['nullable', 'date'],
            'quien_recibe' => ['nullable', 'string', 'max:120'],
            'id_tipo_donacion' => ['nullable', 'integer'],
            'persona_gestiono' => ['nullable', 'string', 'max:120'],

            'costo_logistica' => ['nullable', 'numeric', 'min:0'],
            'descripcion_logistica' => ['nullable', 'string'],

            'impacto_personas' => ['nullable', 'integer', 'min:0'],
            'comentarios' => ['nullable', 'string'],

            'recibo_empresa' => ['nullable', 'in:0,1'],
            'ref_osshp' => ['exclude_unless:recibo_empresa,1', 'nullable', 'string', 'max:80'],
            'fecha_ref_osshp' => ['exclude_unless:recibo_empresa,1', 'nullable', 'date'],
            'ref_sat' => ['nullable', 'string', 'max:80'],
            'fecha_ref_sat' => ['exclude_unless:recibo_empresa,1', 'nullable', 'date'],
        ]);

        $data['recibo_empresa'] = (string) $request->input('recibo_empresa') === '1' ? 1 : 0;
        $items = $this->prepararItems($data);
        $guardados = [];
        try {
            DB::transaction(function () use ($id, $data, $items, $request, &$guardados) {
                $actual = Donacion::where('id_donacion', $id)->lockForUpdate()->firstOrFail();
                abort_if((int) $actual->bloqueado === 1, 403, 'Este registro está bloqueado.');
                $actual->fill($data)->save();
                $actual->detalles()->delete();
                $actual->detalles()->createMany($items);
                if ($data['recibo_empresa'] === 1) {
                    DocumentosDonacion::guardar($id, $request, $guardados);
                }
            });
        } catch (\Throwable $error) {
            DocumentosDonacion::limpiar($guardados);
            throw $error;
        }

        return redirect()->route('donaciones.index')->with('success', 'Donación actualizada.');
    }

    public function destroy($id)
    {

         if (!$this->puedeModificarDonaciones()) {
               abort(403, 'No tienes permiso para editar registros de Caja Chica.');
         }

        $donacion = Donacion::where('id_donacion', $id)->firstOrFail();

        if ((int)($donacion->bloqueado ?? 0) === 1) {
            return back()->with('error', 'Este registro está bloqueado. No se puede modificar ni eliminar.');
        }

        $rutas = DB::transaction(function () use ($id) {
            $actual = Donacion::where('id_donacion', $id)->lockForUpdate()->firstOrFail();
            abort_if((int) $actual->bloqueado === 1, 403, 'Este registro está bloqueado.');
            $rutas = DocumentosDonacion::listar($id)->pluck('ruta')->all();
            $actual->delete();
            return $rutas;
        });
        DocumentosDonacion::limpiar($rutas);

        return redirect()->route('donaciones.index')->with('success', 'Donación eliminada.');
    }

    public function show($id)
    {
        $request = request();
        $donacion = DB::table('donaciones as d')
            ->leftJoin('tipos_donacion as td', 'td.id_tipo_donacion', '=', 'd.id_tipo_donacion')
            ->leftJoin('ubicaciones as u', 'u.id_ubicacion', '=', 'd.id_ubicacion')
            ->leftJoin('proyectos as p', 'p.id_proyecto', '=', 'd.id_proyecto')
            ->select(
                'd.*',
                DB::raw('td.nombre as tipo_donacion'),
                DB::raw('u.nombre as ubicacion'),
                DB::raw('p.nombre as proyecto')
            )
            ->where('d.id_donacion', $id)
            ->first();

        abort_if(!$donacion, 404);
        if ($request->filled('documento')) {
            return DocumentosDonacion::abrir($id, (string) $request->query('documento'));
        }
        $documentos = DocumentosDonacion::listar($id);
        $detalles = ReporteDonaciones::detalles($id);
        if ($detalles->isNotEmpty()) {
            $donacion->tipo_donacion = $detalles->pluck('tipo_donacion')->unique()->implode(', ');
            $donacion->proyecto = $detalles->pluck('proyecto')->unique()->implode(', ');
        }

        return view('donaciones.show', compact('donacion', 'detalles', 'documentos'));
    }

    public function index(Request $request)
    {
        $q        = trim($request->get('q', ''));
        $from     = $request->get('from');      // YYYY-MM-DD
        $to       = $request->get('to');        // YYYY-MM-DD
        $tipo     = $request->get('tipo');      // id_tipo_donacion
        $proyecto = $request->get('proyecto');  // id_proyecto

        $base = ReporteDonaciones::base($tipo, $proyecto)
            ->leftJoin('usuarios as u', 'u.id_usuario', '=', 'd.id_usuario')
            ->leftJoin('ubicaciones as ub', 'ub.id_ubicacion', '=', 'd.id_ubicacion')
            ->leftJoin('tipos_donacion as td', 'td.id_tipo_donacion', '=', 'd.id_tipo_donacion')
            ->leftJoin('proyectos as p', 'p.id_proyecto', '=', 'd.id_proyecto');

        // ===== FILTROS =====
        if ($q !== '') {
            $base->where(function ($w) use ($q) {
                $w->where('d.empresa', 'like', "%{$q}%")
                  ->orWhere('d.nit', 'like', "%{$q}%")
                  ->orWhere('d.contacto', 'like', "%{$q}%")
                  ->orWhere('d.quien_recibe', 'like', "%{$q}%")
                  ->orWhere('d.ref_osshp', 'like', "%{$q}%")
                  ->orWhere('d.ref_sat', 'like', "%{$q}%");
            });
        }

        if ($from) $base->whereDate('d.fecha_despachada', '>=', $from);
        if ($to) $base->whereDate('d.fecha_despachada', '<=', $to);



        // ===== STATS PARA CARDS (respetan filtros) =====
        $stats = (clone $base)
            ->selectRaw('
                COUNT(DISTINCT d.id_donacion) AS total_donaciones,
                COALESCE(SUM(CAST(COALESCE(dm.monto, d.valor_total_donacion) AS DECIMAL(12,2))), 0) AS total_dinero,
                COALESCE(SUM(CAST(d.impacto_personas AS UNSIGNED)), 0) AS total_impacto
            ')
            ->first();

        // ===== TABLA RESUMEN POR TIPO (respetan filtros) =====
        $resumenTipos = ReporteDonaciones::resumen($base, $tipo, $proyecto);

        $totalGeneralTipos = $resumenTipos->sum('total');

        // ===== GRÁFICAS (respetan filtros) =====
        $porTipo = $resumenTipos->map(fn ($r) => (object) ['label' => $r->tipo, 'total' => $r->total]);

        $porProyecto = ReporteDonaciones::porProyecto($base, $tipo, $proyecto);

        // ===== LISTADO =====
        $donaciones = (clone $base)
            ->select(
                'd.id_donacion',
                'd.fecha_despachada',
                'd.empresa',
                'd.nit',
                'd.contacto',
                DB::raw("COALESCE(dm.monto, d.valor_total_donacion) as valor_total_donacion"),
                'ub.nombre as ubicacion',
                DB::raw("COALESCE(dm.tipos, td.nombre, 'Sin clasificar') as tipo_donacion"),
                DB::raw("COALESCE(dm.proyectos, p.nombre, 'Sin proyecto') as proyecto"),
                'd.impacto_personas',
                DB::raw("CONCAT(u.nombre,' ',u.apellido) as usuario"),
                'd.bloqueado as bloqueado',
                'd.created_at'
            )
            ->orderByDesc('d.id_donacion')
            ->paginate(20)
            ->appends($request->query());

        // ===== CATÁLOGOS PARA FILTROS =====
        $tipos = DB::table('tipos_donacion')
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get();

        $proyectos = DB::table('proyectos')
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get();

        return view('donaciones.index', compact(
            'donaciones', 'q', 'from', 'to', 'tipo', 'proyecto',
            'tipos', 'proyectos', 'stats',
            'porTipo', 'porProyecto',
            'resumenTipos', 'totalGeneralTipos'
        ));
    }





public function exportExcel(Request $request)
{
    $rows = $this->buildExportQueryAll($request)->get();
        $rows->each(function ($row) {
            $row->valor_total_donacion = $row->monto_reporte;
            unset($row->monto_reporte);
        });

    if ($rows->isEmpty()) {
        return back()->with('error', 'No hay datos para exportar.');
    }

    $headers = array_keys((array) $rows->first());

    $exportData = [];
    $exportData[] = $headers;

    foreach ($rows as $row) {
        $exportData[] = array_values((array) $row);
    }

    $totalGeneral = $rows->sum(function ($row) {
        return (float) str_replace(
            [',', 'Q', ' '],
            '',
            $row->valor_total_donacion ?? 0
        );
    });

    $indiceValor = array_search(
        'valor_total_donacion',
        $headers,
        true
    );

    $filaVacia = array_fill(0, count($headers), '');
    $filaTotal = array_fill(0, count($headers), '');

    if ($indiceValor !== false) {
        $filaTotal[$indiceValor] = $totalGeneral;

        if ($indiceValor > 0) {
            $filaTotal[$indiceValor - 1] = 'TOTAL GENERAL';
        } else {
            $filaTotal[0] = $totalGeneral;
        }
    }

    $exportData[] = $filaVacia;
    $exportData[] = $filaTotal;

    $fileName = 'donaciones_completo_' .
        now()->format('Ymd_His') .
        '.xlsx';

    return Excel::download(
        new \App\Exports\ArrayExport($exportData),
        $fileName
    );
}

    public function exportPdf(Request $request)
{
       //abort(500, 'CONTROLADOR ACTUALIZADO');
    $donaciones = $this->buildExportQuery($request)->get();

    if ($donaciones->isEmpty()) {
        return back()->with('error', 'No hay datos para exportar.');
    }

    $totalGeneral = $donaciones->sum('valor_total_donacion');

    $totalImpacto = $donaciones->sum('impacto_personas');

    $pdf = Pdf::loadView('exports.donaciones_pdf', [
        'donaciones'   => $donaciones,
        'filtros'      => $request->query(),
        'totalGeneral' => (float) $totalGeneral,
        'totalImpacto' => (int) $totalImpacto,
    ])->setPaper('a4', 'landscape');

    return $pdf->download(
        'donaciones_' . now()->format('Ymd_His') . '.pdf'
    );
}

    /**
     * Query reutilizable para export (respeta filtros del dashboard)
     */
    private function buildExportQuery(Request $request)
    {
        $q        = trim($request->get('q', ''));
        $from     = $request->get('from');
        $to       = $request->get('to');
        $tipo     = $request->get('tipo');
        $proyecto = $request->get('proyecto');

        $base = ReporteDonaciones::base($tipo, $proyecto)
            ->leftJoin('usuarios as u', 'u.id_usuario', '=', 'd.id_usuario')
            ->leftJoin('ubicaciones as ub', 'ub.id_ubicacion', '=', 'd.id_ubicacion')
            ->leftJoin('tipos_donacion as td', 'td.id_tipo_donacion', '=', 'd.id_tipo_donacion')
            ->leftJoin('proyectos as p', 'p.id_proyecto', '=', 'd.id_proyecto');

        if ($q !== '') {
            $base->where(function ($w) use ($q) {
                $w->where('d.empresa', 'like', "%{$q}%")
                  ->orWhere('d.nit', 'like', "%{$q}%")
                  ->orWhere('d.contacto', 'like', "%{$q}%")
                  ->orWhere('d.quien_recibe', 'like', "%{$q}%")
                  ->orWhere('d.ref_osshp', 'like', "%{$q}%")
                  ->orWhere('d.ref_sat', 'like', "%{$q}%");
            });
        }

        if ($from) $base->whereDate('d.fecha_despachada', '>=', $from);
        if ($to) $base->whereDate('d.fecha_despachada', '<=', $to);



        return $base->select(
            'd.id_donacion',
            'd.fecha_despachada',
            'd.empresa',
            'd.nit',
            'd.contacto',
            DB::raw("COALESCE(dm.monto, d.valor_total_donacion) as valor_total_donacion"),
            'ub.nombre as ubicacion',
            DB::raw("COALESCE(dm.tipos, td.nombre, 'Sin clasificar') as tipo_donacion"),
            DB::raw("COALESCE(dm.proyectos, p.nombre, 'Sin proyecto') as proyecto"),
           // DB::raw("CONCAT(u.nombre,' ',u.apellido) as usuario"),
            'd.persona_gestiono',
            'd.impacto_personas',
            'd.descripcion'
        )->orderByDesc('d.id_donacion');
    }

    /**
     * Query de exportación COMPLETA (todas las columnas de donaciones)
     * Respeta filtros del dashboard
     */
    private function buildExportQueryAll(Request $request)
    {
        $q        = trim($request->get('q', ''));
        $from     = $request->get('from');
        $to       = $request->get('to');
        $tipo     = $request->get('tipo');
        $proyecto = $request->get('proyecto');

        $base = ReporteDonaciones::base($tipo, $proyecto)
            ->leftJoin('usuarios as u', 'u.id_usuario', '=', 'd.id_usuario')
            ->leftJoin('ubicaciones as ub', 'ub.id_ubicacion', '=', 'd.id_ubicacion')
            ->leftJoin('tipos_donacion as td', 'td.id_tipo_donacion', '=', 'd.id_tipo_donacion')
            ->leftJoin('proyectos as p', 'p.id_proyecto', '=', 'd.id_proyecto');

        if ($q !== '') {
            $base->where(function ($w) use ($q) {
                $w->where('d.empresa', 'like', "%{$q}%")
                  ->orWhere('d.nit', 'like', "%{$q}%")
                  ->orWhere('d.contacto', 'like', "%{$q}%")
                  ->orWhere('d.quien_recibe', 'like', "%{$q}%")
                  ->orWhere('d.ref_osshp', 'like', "%{$q}%")
                  ->orWhere('d.ref_sat', 'like', "%{$q}%");
            });
        }

        if ($from) $base->whereDate('d.fecha_despachada', '>=', $from);
        if ($to) $base->whereDate('d.fecha_despachada', '<=', $to);



        return $base->select([
                'd.*',
                DB::raw("COALESCE(dm.monto, d.valor_total_donacion) as monto_reporte"),
                DB::raw("CONCAT(u.nombre,' ',u.apellido) AS usuario_nombre"),
                'ub.nombre AS ubicacion_nombre',
                DB::raw("COALESCE(dm.tipos, td.nombre, 'Sin clasificar') AS tipo_donacion_nombre"),
                DB::raw("COALESCE(dm.proyectos, p.nombre, 'Sin proyecto') AS proyecto_nombre"),
            ])
            ->orderByDesc('d.id_donacion');
    }

    /**
     * ✅ ACTA PDF (abre en otra pestaña desde el botón "Acta" del index)
     */
    public function pdf($id)
    {
        $donacion = DB::table('donaciones as d')
            ->leftJoin('tipos_donacion as td', 'td.id_tipo_donacion', '=', 'd.id_tipo_donacion')
            ->leftJoin('ubicaciones as u', 'u.id_ubicacion', '=', 'd.id_ubicacion')
            ->leftJoin('proyectos as p', 'p.id_proyecto', '=', 'd.id_proyecto')
            ->select(
                'd.*',
                DB::raw('td.nombre as tipo_donacion'),
                DB::raw('u.nombre as ubicacion'),
                DB::raw('p.nombre as proyecto')
            )
            ->where('d.id_donacion', $id)
            ->first();

        abort_if(!$donacion, 404);
        $detalles = ReporteDonaciones::detalles($id);
        if ($detalles->isNotEmpty()) {
            $donacion->tipo_donacion = $detalles->pluck('tipo_donacion')->unique()->implode(', ');
            $donacion->proyecto = $detalles->pluck('proyecto')->unique()->implode(', ');
        }

        $pdf = Pdf::loadView('donaciones.pdf', ['donacion' => $donacion, 'detalles' => $detalles])
            ->setPaper('a4', 'portrait');

        return $pdf->stream("acta-donacion-{$donacion->id_donacion}.pdf");
    }

    /**
     * ✅ Toggle AJAX para bloquear / desbloquear
     */
    public function toggleBloqueo($id)
    {
        $donacion = Donacion::where('id_donacion', $id)->firstOrFail();
        $donacion->bloqueado = (int)($donacion->bloqueado ?? 0) === 1 ? 0 : 1;
        $donacion->save();

        return response()->json(['ok' => true, 'bloqueado' => (int)$donacion->bloqueado]);
    }


       private function puedeModificarDonaciones(): bool
    {
        $u = session('user');
        $rolName = strtoupper(trim($u['rol'] ?? $u['nombre_rol'] ?? ''));

        return in_array($rolName, ['ADMIN', 'GESTOR', 'DONACIONES', 'SECRETARIA'], true);
    }


    /** Calculate exact cents and persist only validated item fields. */
    private function prepararItems(array &$data): array
    {
        $items = [];
        $centavos = 0;
        $unidades = 0;
        $hayUnidades = false;
        foreach ($data['items'] as $item) {
            $partes = explode('.', (string) $item['monto'], 2);
            $monto = ((int) $partes[0] * 100) + (int) str_pad($partes[1] ?? '', 2, '0');
            $centavos += $monto;
            $cantidad = isset($item['unidades']) && $item['unidades'] !== '' ? (int) $item['unidades'] : null;
            $hayUnidades = $hayUnidades || $cantidad !== null;
            $unidades += $cantidad ?? 0;
            $items[] = [
                'id_tipo_donacion' => (int) $item['id_tipo_donacion'],
                'id_proyecto' => (int) $item['id_proyecto'],
                'descripcion' => $item['descripcion'] ?? null,
                'unidades' => $cantidad,
                'monto' => intdiv($monto, 100) . '.' . str_pad((string) ($monto % 100), 2, '0', STR_PAD_LEFT),
            ];
        }
        if ($centavos > 999999999999 || $unidades > 4294967295) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'items' => 'El total de montos o unidades excede la capacidad del registro.',
            ]);
        }
        unset($data['items']);
        $data['valor_total_donacion'] = intdiv($centavos, 100) . '.' . str_pad((string) ($centavos % 100), 2, '0', STR_PAD_LEFT);
        $data['unidades'] = $hayUnidades ? $unidades : null;
        $data['unidades_entrega'] = $data['unidades'];
        // For a mixed receipt there is no single parent category.
        $tipos = array_unique(array_column($items, 'id_tipo_donacion'));
        $data['id_tipo_donacion'] = count($tipos) === 1 ? reset($tipos) : null;
        $proyectos = array_unique(array_column($items, 'id_proyecto'));
        $data['id_proyecto'] = count($proyectos) === 1 ? reset($proyectos) : null;
        return $items;
    }
}
