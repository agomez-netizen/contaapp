<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\ReporteDonaciones;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
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
        // Por Tipo
        $porTipo = $resumenTipos->map(fn ($r) => (object) ['label' => $r->tipo, 'total' => $r->total]);

        // Por Proyecto (excluimos null -> adiós NaN% y adiós 'null' en leyenda)
        $porProyecto = ReporteDonaciones::porProyecto($base, $tipo, $proyecto);


        // ===== AVANCES POR PROYECTO =====
            $avancesPorProyecto = DB::table('avances as a')
                ->join('proyectos as p', 'p.id_proyecto', '=', 'a.id_proyecto')
                ->selectRaw('
                    p.nombre AS label,
                    COUNT(a.id_avance) AS total
                ')
                ->groupBy('p.id_proyecto', 'p.nombre')
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
                DB::raw("COALESCE(dm.monto, d.valor_total_donacion) as valor_total_donacion"),
                'ub.nombre as ubicacion',
                DB::raw("COALESCE(dm.tipos, td.nombre, 'Sin clasificar') as tipo_donacion"),
                DB::raw("COALESCE(dm.proyectos, p.nombre, 'Sin proyecto') as proyecto"),
                'd.impacto_personas',
                DB::raw("CONCAT(u.nombre,' ',u.apellido) as usuario"),
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

        return view('dashboard', compact(
            'donaciones', 'q', 'from', 'to', 'tipo', 'proyecto',
            'tipos', 'proyectos', 'stats',
            'porTipo',
            'porProyecto',
            'avancesPorProyecto',
            'resumenTipos',
            'totalGeneralTipos',



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
            'totalGeneral' => $donaciones->sum('valor_total_donacion'),
            'totalImpacto' => $donaciones->sum('impacto_personas'),
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
     * Scatter: Dinero vs Impacto (para Chart.js)
     * Respeta filtros del dashboard
     */
    public function scatter(Request $request)
    {
        $q        = trim($request->get('q', ''));
        $from     = $request->get('from');
        $to       = $request->get('to');
        $tipo     = $request->get('tipo');
        $proyecto = $request->get('proyecto');

        $rows = ReporteDonaciones::base($tipo, $proyecto)
            ->leftJoin('proyectos as p', 'p.id_proyecto', '=', 'd.id_proyecto')
            ->select([
                'd.id_donacion as id',
                'd.fecha_despachada as fecha',
                'd.empresa as empresa',
                DB::raw("COALESCE(dm.proyectos, p.nombre, 'Sin proyecto') as proyecto"),
                DB::raw("COALESCE(dm.monto, d.valor_total_donacion) as valor"),
                'd.impacto_personas as impacto',
            ])
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function ($w) use ($q) {
                    $w->where('d.empresa', 'like', "%{$q}%")
                      ->orWhere('d.nit', 'like', "%{$q}%")
                      ->orWhere('d.contacto', 'like', "%{$q}%")
                      ->orWhere('d.quien_recibe', 'like', "%{$q}%")
                      ->orWhere('d.ref_osshp', 'like', "%{$q}%")
                      ->orWhere('d.ref_sat', 'like', "%{$q}%");
                });
            })
            ->when($from, fn($qq) => $qq->whereDate('d.fecha_despachada', '>=', $from))
            ->when($to, fn($qq) => $qq->whereDate('d.fecha_despachada', '<=', $to))
            ->whereRaw("COALESCE(dm.monto, d.valor_total_donacion) IS NOT NULL")
            ->whereNotNull('d.impacto_personas')
            ->orderByDesc('d.id_donacion')
            ->limit(800)
            ->get();

        $points = $rows->map(fn($r) => [
            'x' => (float) $r->valor,
            'y' => (float) $r->impacto,
            'meta' => [
                'empresa' => $r->empresa ?? 'Sin empresa',
                'proyecto' => $r->proyecto ?? 'Sin proyecto',
                'fecha' => $r->fecha,
                'id' => $r->id,
            ],
        ]);

        return response()->json([
            'points' => $points,
        ]);
    }


    public function exportResumenPdf(Request $request)
{
    $q        = trim($request->get('q', ''));
    $from     = $request->get('from');
    $to       = $request->get('to');
    $tipo     = $request->get('tipo');
    $proyecto = $request->get('proyecto');

    $base = ReporteDonaciones::base($tipo, $proyecto)
        ->leftJoin(
            'tipos_donacion as td',
            'td.id_tipo_donacion',
            '=',
            'd.id_tipo_donacion'
        )
        ->leftJoin(
            'proyectos as p',
            'p.id_proyecto',
            '=',
            'd.id_proyecto'
        );

    if ($q !== '') {
        $base->where(function ($consulta) use ($q) {
            $consulta
                ->where('d.empresa', 'like', "%{$q}%")
                ->orWhere('d.nit', 'like', "%{$q}%")
                ->orWhere('d.contacto', 'like', "%{$q}%")
                ->orWhere('d.quien_recibe', 'like', "%{$q}%")
                ->orWhere('d.ref_osshp', 'like', "%{$q}%")
                ->orWhere('d.ref_sat', 'like', "%{$q}%");
        });
    }

    if ($from) {
        $base->whereDate('d.fecha_despachada', '>=', $from);
    }

    if ($to) {
        $base->whereDate('d.fecha_despachada', '<=', $to);
    }





    $stats = (clone $base)
        ->selectRaw('
            COUNT(DISTINCT d.id_donacion) AS total_donaciones,
            COALESCE(
                SUM(CAST(COALESCE(dm.monto, d.valor_total_donacion) AS DECIMAL(12,2))),
                0
            ) AS total_dinero,
            COALESCE(
                SUM(CAST(d.impacto_personas AS UNSIGNED)),
                0
            ) AS total_impacto
        ')
        ->first();

    $resumenTipos = ReporteDonaciones::resumen($base, $tipo, $proyecto);

    $totalGeneralTipos = $resumenTipos->sum('total');

    $pdf = Pdf::loadView('exports.dashboard_resumen_pdf', [
        'stats'               => $stats,
        'resumenTipos'        => $resumenTipos,
        'totalGeneralTipos'   => $totalGeneralTipos,
        'graficaTipo'         => $request->input('grafica_tipo'),
        'graficaProyecto'     => $request->input('grafica_proyecto'),
        'graficaAvances'      => $request->input('grafica_avances'),
        'from'                => $from,
        'to'                  => $to,
    ])->setPaper('a4', 'landscape');

    return $pdf->download(
        'resumen_donaciones_' . now()->format('Ymd_His') . '.pdf'
    );
}
}
