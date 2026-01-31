<?php

namespace App\Http\Controllers;

use App\Models\Donacion;
use App\Models\Ubicacion;
use App\Models\TipoDonacion;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class DonacionController extends Controller
{
    public function create()
    {
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

        $data = $request->validate([
            'fecha_despachada' => ['nullable', 'date'],
            'empresa' => ['nullable', 'string', 'max:180'],
            'nit' => ['nullable', 'string', 'max:50'],
            'contacto' => ['nullable', 'string', 'max:120'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'correo' => ['nullable', 'email', 'max:150'],

            'unidades' => ['nullable', 'integer', 'min:0'],
            'descripcion' => ['nullable', 'string'],
            'valor_total_donacion' => ['nullable', 'numeric', 'min:0'],

            'id_ubicacion' => ['nullable', 'integer'],
            'fecha_recibe' => ['nullable', 'date'],
            'quien_recibe' => ['nullable', 'string', 'max:120'],
            'id_tipo_donacion' => ['nullable', 'integer'],
            'unidades_entrega' => ['nullable', 'integer', 'min:0'],
            'persona_gestiono' => ['nullable', 'string', 'max:120'],

            'precio_mercado_unidad' => ['nullable', 'numeric', 'min:0'],
            'total_mercado' => ['nullable', 'numeric', 'min:0'],
            'referencia_mercado' => ['nullable', 'string', 'max:180'],
            'costo_logistica' => ['nullable', 'numeric', 'min:0'],
            'descripcion_logistica' => ['nullable', 'string'],

            'id_proyecto' => ['nullable', 'integer'],
            'impacto_personas' => ['nullable', 'integer', 'min:0'],
            'comentarios' => ['nullable', 'string'],

            'recibo_empresa' => ['nullable', 'in:0,1'],
            'ref_osshp' => ['nullable', 'string', 'max:80'],
            'fecha_ref_osshp' => ['nullable', 'date'],
            'ref_sat' => ['nullable', 'string', 'max:80'],
            'fecha_ref_sat' => ['nullable', 'date'],
        ]);

        $data['id_usuario'] = $u['id_usuario'];

        // por defecto desbloqueado si la columna existe
        if (!array_key_exists('bloqueado', $data)) {
            $data['bloqueado'] = 0;
        }

        Donacion::create($data);

        return redirect()
            ->route('donaciones.index')
            ->with('success', 'Donación guardada correctamente.');
    }

    public function edit($id)
    {
        $donacion = Donacion::where('id_donacion', $id)->firstOrFail();

        if ((int)($donacion->bloqueado ?? 0) === 1) {
            return back()->with('error', 'Este registro está bloqueado. No se puede modificar ni eliminar.');
        }

        $ubicaciones = Ubicacion::where('activo', 1)->orderBy('nombre')->get();
        $tipos = TipoDonacion::where('activo', 1)->orderBy('nombre')->get();
        $proyectos = Proyecto::where('activo', 1)->orderBy('nombre')->get();

        return view('donaciones.edit', compact('donacion', 'ubicaciones', 'tipos', 'proyectos'));
    }

    public function update(Request $request, $id)
    {
        $donacion = Donacion::where('id_donacion', $id)->firstOrFail();

        if ((int)($donacion->bloqueado ?? 0) === 1) {
            return back()->with('error', 'Este registro está bloqueado. No se puede modificar ni eliminar.');
        }

        $data = $request->validate([
            'fecha_despachada' => ['nullable', 'date'],
            'empresa' => ['nullable', 'string', 'max:180'],
            'nit' => ['nullable', 'string', 'max:50'],
            'contacto' => ['nullable', 'string', 'max:120'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'correo' => ['nullable', 'email', 'max:150'],

            'unidades' => ['nullable', 'integer', 'min:0'],
            'descripcion' => ['nullable', 'string'],
            'valor_total_donacion' => ['nullable', 'numeric', 'min:0'],

            'id_ubicacion' => ['nullable', 'integer'],
            'fecha_recibe' => ['nullable', 'date'],
            'quien_recibe' => ['nullable', 'string', 'max:120'],
            'id_tipo_donacion' => ['nullable', 'integer'],
            'unidades_entrega' => ['nullable', 'integer', 'min:0'],
            'persona_gestiono' => ['nullable', 'string', 'max:120'],

            'precio_mercado_unidad' => ['nullable', 'numeric', 'min:0'],
            'total_mercado' => ['nullable', 'numeric', 'min:0'],
            'referencia_mercado' => ['nullable', 'string', 'max:180'],
            'costo_logistica' => ['nullable', 'numeric', 'min:0'],
            'descripcion_logistica' => ['nullable', 'string'],

            'id_proyecto' => ['nullable', 'integer'],
            'impacto_personas' => ['nullable', 'integer', 'min:0'],
            'comentarios' => ['nullable', 'string'],

            'recibo_empresa' => ['nullable', 'in:0,1'],
            'ref_osshp' => ['nullable', 'string', 'max:80'],
            'fecha_ref_osshp' => ['nullable', 'date'],
            'ref_sat' => ['nullable', 'string', 'max:80'],
            'fecha_ref_sat' => ['nullable', 'date'],
        ]);

        $donacion->fill($data);
        $donacion->save();

        return redirect()->route('donaciones.index')->with('success', 'Donación actualizada.');
    }

    public function destroy($id)
    {
        $donacion = Donacion::where('id_donacion', $id)->firstOrFail();

        if ((int)($donacion->bloqueado ?? 0) === 1) {
            return back()->with('error', 'Este registro está bloqueado. No se puede modificar ni eliminar.');
        }

        $donacion->delete();

        return redirect()->route('donaciones.index')->with('success', 'Donación eliminada.');
    }

    public function show($id)
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

        return view('donaciones.show', compact('donacion'));
    }

    public function index(Request $request)
    {
        $q        = trim($request->get('q', ''));
        $from     = $request->get('from');      // YYYY-MM-DD
        $to       = $request->get('to');        // YYYY-MM-DD
        $tipo     = $request->get('tipo');      // id_tipo_donacion
        $proyecto = $request->get('proyecto');  // id_proyecto

        $base = DB::table('donaciones as d')
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
        if ($tipo) $base->where('d.id_tipo_donacion', $tipo);
        if ($proyecto) $base->where('d.id_proyecto', $proyecto);

        // ===== STATS PARA CARDS (respetan filtros) =====
        $stats = (clone $base)
            ->selectRaw('
                COUNT(DISTINCT d.id_donacion) AS total_donaciones,
                COALESCE(SUM(CAST(d.valor_total_donacion AS DECIMAL(12,2))), 0) AS total_dinero,
                COALESCE(SUM(CAST(d.impacto_personas AS UNSIGNED)), 0) AS total_impacto
            ')
            ->first();

        // ===== TABLA RESUMEN POR TIPO (respetan filtros) =====
        $resumenTipos = (clone $base)
            ->whereNotNull('td.nombre')
            ->whereNotNull('d.valor_total_donacion')
            ->whereRaw("CAST(d.valor_total_donacion AS DECIMAL(12,2)) > 0")
            ->groupBy('d.id_tipo_donacion', 'td.nombre')
            ->selectRaw("
                td.nombre AS tipo,
                COALESCE(SUM(CAST(d.valor_total_donacion AS DECIMAL(12,2))), 0) AS total
            ")
            ->orderByDesc('total')
            ->get();

        $totalGeneralTipos = $resumenTipos->sum('total');

        // ===== GRÁFICAS (respetan filtros) =====
        $porTipo = (clone $base)
            ->whereNotNull('td.nombre')
            ->whereNotNull('d.valor_total_donacion')
            ->whereRaw("CAST(d.valor_total_donacion AS DECIMAL(12,2)) > 0")
            ->groupBy('d.id_tipo_donacion', 'td.nombre')
            ->selectRaw("
                td.nombre AS label,
                COALESCE(SUM(CAST(d.valor_total_donacion AS DECIMAL(12,2))), 0) AS total
            ")
            ->orderByDesc('total')
            ->get();

        $porProyecto = (clone $base)
            ->whereNotNull('p.nombre')
            ->whereNotNull('d.valor_total_donacion')
            ->whereRaw("CAST(d.valor_total_donacion AS DECIMAL(12,2)) > 0")
            ->groupBy('d.id_proyecto', 'p.nombre')
            ->selectRaw("
                p.nombre AS label,
                COALESCE(SUM(CAST(d.valor_total_donacion AS DECIMAL(12,2))), 0) AS total
            ")
            ->orderByDesc('total')
            ->get();

        // ===== LISTADO =====
        $donaciones = (clone $base)
            ->select(
                'd.id_donacion',
                'd.fecha_despachada',
                'd.empresa',
                'd.nit',
                'd.contacto',
                'd.valor_total_donacion',
                'ub.nombre as ubicacion',
                'td.nombre as tipo_donacion',
                'p.nombre as proyecto',
                'd.impacto_personas',
                DB::raw("CONCAT(u.nombre,' ',u.apellido) as usuario"),
                'd.bloqueado as bloqueado',
                'd.created_at'
            )
            ->orderByDesc('d.id_donacion')
            ->paginate(5)
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

        if ($rows->isEmpty()) {
            return back()->with('error', 'No hay datos para exportar.');
        }

        $headers = array_keys((array) $rows->first());

        $exportData = [];
        $exportData[] = $headers;

        foreach ($rows as $row) {
            $exportData[] = array_values((array) $row);
        }

        $fileName = 'donaciones_completo_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(
            new \App\Exports\ArrayExport($exportData),
            $fileName
        );
    }

    public function exportPdf(Request $request)
    {
        $donaciones = $this->buildExportQuery($request)->get();

        $pdf = Pdf::loadView('exports.donaciones_pdf', [
            'donaciones' => $donaciones,
            'filtros' => $request->query(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('donaciones_' . now()->format('Ymd_His') . '.pdf');
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

        $base = DB::table('donaciones as d')
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
        if ($tipo) $base->where('d.id_tipo_donacion', $tipo);
        if ($proyecto) $base->where('d.id_proyecto', $proyecto);

        return $base->select(
            'd.id_donacion',
            'd.fecha_despachada',
            'd.empresa',
            'd.nit',
            'd.contacto',
            'd.valor_total_donacion',
            'ub.nombre as ubicacion',
            'td.nombre as tipo_donacion',
            'p.nombre as proyecto',
            'd.impacto_personas',
            DB::raw("CONCAT(u.nombre,' ',u.apellido) as usuario")
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

        $base = DB::table('donaciones as d')
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
        if ($tipo) $base->where('d.id_tipo_donacion', $tipo);
        if ($proyecto) $base->where('d.id_proyecto', $proyecto);

        return $base->select([
                'd.*',
                DB::raw("CONCAT(u.nombre,' ',u.apellido) AS usuario_nombre"),
                'ub.nombre AS ubicacion_nombre',
                'td.nombre AS tipo_donacion_nombre',
                'p.nombre AS proyecto_nombre',
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

        $pdf = Pdf::loadView('donaciones.pdf', ['donacion' => $donacion])
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
}
