<?php

namespace App\Http\Controllers;

use App\Models\MovimientoFinanciero;
use App\Models\Proyecto;
use App\Models\Subproyecto;
use App\Models\Rubro;
use Illuminate\Http\Request;
use App\Exports\ArrayExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class MovimientoFinancieroController extends Controller
{
    public function index()
    {
        $proyectos = Proyecto::where('activo', 1)
            ->orderBy('nombre', 'asc')
            ->get();

        $subproyectos = Subproyecto::where('activo', 1)
            ->orderBy('nombre', 'asc')
            ->get();

        $rubros = Rubro::where('activo', 1)
            ->orderBy('nombre', 'asc')
            ->get();

        return view('finanzas.index', compact(
            'proyectos',
            'subproyectos',
            'rubros'
        ));
    }



    public function store(Request $request)
    {
        $request->validate([
            'tipo_movimiento' => 'required',
            'id_proyecto' => 'required',
            'tipo_documento' => 'required',
            'fecha_documento' => 'required|date',
            'monto' => 'required|numeric|min:0',
            'archivo' => 'nullable|file|max:5120',
        ]);

        $archivoPath = null;
        $archivoOriginal = null;
        $archivoMime = null;

        if ($request->hasFile('archivo')) {

            $archivo = $request->file('archivo');

            $archivoPath = $archivo->store('finanzas', 'public');

            $archivoOriginal = $archivo->getClientOriginalName();

            $archivoMime = $archivo->getClientMimeType();
        }

        MovimientoFinanciero::create([

            'tipo_movimiento' => $request->tipo_movimiento,

            'oficina' => $request->oficina,

            'id_proyecto' => $request->id_proyecto,

            'id_subproyecto' => $request->id_subproyecto,

            'id_rubro' => $request->id_rubro,

            'tipo_documento' => $request->tipo_documento,

            'no_documento' => $request->no_documento,

            'fecha_documento' => $request->fecha_documento,

            'empresa' => $request->empresa,

            'proveedor' => $request->proveedor,

            'monto' => $request->monto,

            'descripcion' => $request->descripcion,

            'archivo_path' => $archivoPath,

            'archivo_original' => $archivoOriginal,

            'archivo_mime' => $archivoMime,

            'id_usuario' => session('id_usuario')
                ?? session('usuario.id_usuario')
                ?? auth()->id()
                ?? 1,

            'accion' => 'Creado',
        ]);

        return redirect()
            ->route('finanzas.historial')
            ->with('success', 'Movimiento financiero guardado correctamente.');
    }

    private function queryHistorial($request)
{
    $query = MovimientoFinanciero::with([
        'proyecto',
        'subproyecto',
        'rubro',
        'usuario'
    ]);

    if ($request->filled('tipo_movimiento')) {
        $query->where('tipo_movimiento', $request->tipo_movimiento);
    }

    if ($request->filled('id_proyecto')) {
        $query->where('id_proyecto', $request->id_proyecto);
    }

    if ($request->filled('id_subproyecto')) {
        $query->where('id_subproyecto', $request->id_subproyecto);
    }

    if ($request->filled('id_rubro')) {
        $query->where('id_rubro', $request->id_rubro);
    }

    if ($request->filled('fecha_inicio')) {
        $query->whereDate('fecha_documento', '>=', $request->fecha_inicio);
    }

    if ($request->filled('fecha_fin')) {
        $query->whereDate('fecha_documento', '<=', $request->fecha_fin);
    }

    return $query->orderBy('fecha_documento', 'desc')
        ->orderBy('id_movimiento', 'desc');
}

public function historial(Request $request)
{
    $movimientos = $this->queryHistorial($request)->get();

    $proyectos = Proyecto::where('activo', 1)->orderBy('nombre')->get();
    $subproyectos = Subproyecto::where('activo', 1)->orderBy('nombre')->get();
    $rubros = Rubro::where('activo', 1)->orderBy('nombre')->get();

    return view('finanzas.historial', compact(
        'movimientos',
        'proyectos',
        'subproyectos',
        'rubros'
    ));
}

    public function exportar(Request $request)
{
    $movimientos = $this->queryHistorial($request)->get();

    $data = [];

    $data[] = [
        'Movimiento',
        'Tipo Documento',
        'No Documento',
        'Fecha',
        'Proyecto',
        'Subproyecto',
        'Rubro',
        'Empresa / Proveedor',
        'Monto',
        'Usuario',
    ];

    foreach ($movimientos as $mov) {

        $data[] = [

            $mov->tipo_movimiento,

            $mov->tipo_documento,

            $mov->no_documento,

            Carbon::parse($mov->fecha_documento)
                ->format('d/m/Y'),

            $mov->proyecto->nombre ?? '',

            $mov->subproyecto->nombre ?? '',

            $mov->rubro->nombre ?? '',

            $mov->empresa ?? $mov->proveedor,

            $mov->monto,

            $mov->usuario->nombre ?? '',

        ];
    }

    return Excel::download(
        new ArrayExport($data),
        'historial_financiero.xlsx'
    );
}
}
