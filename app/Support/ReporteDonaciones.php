<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

class ReporteDonaciones
{
    /** Detalle actual o una sola partida histórica si el recibo no tiene detalle. */
    private static function partidas()
    {
        $historicas = DB::table('donaciones as dl')
            ->select('dl.id_donacion', 'dl.id_tipo_donacion', 'dl.id_proyecto', 'dl.valor_total_donacion as monto')
            ->whereNotExists(function ($q) {
                $q->selectRaw('1')->from('donacion_detalles as dx')
                    ->whereColumn('dx.id_donacion', 'dl.id_donacion');
            });
        return DB::table('donacion_detalles')
            ->select('id_donacion', 'id_tipo_donacion', 'id_proyecto', 'monto')
            ->unionAll($historicas);
    }

    private static function partidasFiltradas($tipo = null, $proyecto = null)
    {
        // Ambos filtros se aplican a la MISMA partida del recibo.
        return DB::query()->fromSub(self::partidas(), 'it')
            ->when($tipo, fn ($q) => $q->where('it.id_tipo_donacion', $tipo))
            ->when($proyecto, fn ($q) => $q->where('it.id_proyecto', $proyecto));
    }

    /** Una fila por recibo para no multiplicar el impacto ni el conteo. */
    public static function base($tipo = null, $proyecto = null)
    {
        $montos = self::partidasFiltradas($tipo, $proyecto)
            ->leftJoin('tipos_donacion as dt', 'dt.id_tipo_donacion', '=', 'it.id_tipo_donacion')
            ->leftJoin('proyectos as dp', 'dp.id_proyecto', '=', 'it.id_proyecto')
            ->selectRaw("it.id_donacion, SUM(it.monto) as monto,
                GROUP_CONCAT(DISTINCT COALESCE(dt.nombre, 'Sin clasificar') ORDER BY dt.nombre SEPARATOR ', ') as tipos,
                GROUP_CONCAT(DISTINCT COALESCE(dp.nombre, 'Sin proyecto') ORDER BY dp.nombre SEPARATOR ', ') as proyectos")
            ->groupBy('it.id_donacion');
        return DB::table('donaciones as d')
            ->joinSub($montos, 'dm', 'dm.id_donacion', '=', 'd.id_donacion');
    }

    public static function resumen($base, $tipo = null, $proyecto = null)
    {
        return self::partidasFiltradas($tipo, $proyecto)
            ->whereIn('it.id_donacion', (clone $base)->reorder()->select('d.id_donacion'))
            ->leftJoin('tipos_donacion as ti', 'ti.id_tipo_donacion', '=', 'it.id_tipo_donacion')
            ->where('it.monto', '>', 0)
            ->selectRaw("COALESCE(ti.nombre, 'Sin clasificar') as tipo, SUM(it.monto) as total")
            ->groupBy('it.id_tipo_donacion', 'ti.nombre')->orderByDesc('total')->get();
    }

    public static function porProyecto($base, $tipo = null, $proyecto = null)
    {
        return self::partidasFiltradas($tipo, $proyecto)
            ->whereIn('it.id_donacion', (clone $base)->reorder()->select('d.id_donacion'))
            ->leftJoin('proyectos as pi', 'pi.id_proyecto', '=', 'it.id_proyecto')
            ->where('it.monto', '>', 0)
            ->selectRaw("COALESCE(pi.nombre, 'Sin proyecto') as label, SUM(it.monto) as total")
            ->groupBy('it.id_proyecto', 'pi.nombre')->orderByDesc('total')->get();
    }

    public static function detalles($id)
    {
        return DB::table('donacion_detalles as dd')
            ->leftJoin('tipos_donacion as dt', 'dt.id_tipo_donacion', '=', 'dd.id_tipo_donacion')
            ->leftJoin('proyectos as dp', 'dp.id_proyecto', '=', 'dd.id_proyecto')
            ->where('dd.id_donacion', $id)
            ->select('dd.*', DB::raw("COALESCE(dt.nombre, 'Sin clasificar') as tipo_donacion"),
                DB::raw("COALESCE(dp.nombre, 'Sin proyecto') as proyecto"))
            ->orderBy('dd.id_detalle')->get();
    }
}
