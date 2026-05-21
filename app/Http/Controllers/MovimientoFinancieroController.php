<?php

namespace App\Http\Controllers;

use App\Models\MovimientoFinanciero;
use App\Models\Proyecto;
use App\Models\Subproyecto;
use App\Models\Rubro;
use App\Exports\ArrayExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class MovimientoFinancieroController extends Controller
{
    public function index()
    {
        $proyectos = Proyecto::where('activo', 1)->orderBy('nombre')->get();
        $subproyectos = Subproyecto::where('activo', 1)->orderBy('nombre')->get();
        $rubros = Rubro::where('activo', 1)->orderBy('nombre')->get();

        return view('finanzas.index', compact(
            'proyectos',
            'subproyectos',
            'rubros'
        ));
    }

    private function queryHistorial(Request $request)
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

        if ($request->filled('tipo_documento')) {
            $tiposDocumento = $request->input('tipo_documento');

            if (!is_array($tiposDocumento)) {
                $tiposDocumento = [$tiposDocumento];
            }

            $query->whereIn('tipo_documento', $tiposDocumento);
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
        $movimientosTotales = $this->queryHistorial($request)->get();

        $totalQuetzales = $movimientosTotales->sum(function ($mov) {
            return $mov->monto_quetzales ?? $mov->monto ?? 0;
        });

        $totalDolares = $movimientosTotales->sum(function ($mov) {
            return $mov->monto_dolares ?? 0;
        });

        $movimientos = $this->queryHistorial($request)
            ->paginate(5)
            ->withQueryString();

        $proyectos = Proyecto::where('activo', 1)->orderBy('nombre')->get();
        $subproyectos = Subproyecto::where('activo', 1)->orderBy('nombre')->get();
        $rubros = Rubro::where('activo', 1)->orderBy('nombre')->get();

        return view('finanzas.historial', compact(
            'movimientos',
            'proyectos',
            'subproyectos',
            'rubros',
            'totalQuetzales',
            'totalDolares'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_movimiento' => 'required',
            'id_proyecto' => 'required',
            'tipo_documento' => 'required',
            'fecha_documento' => 'required|date',
            'monto_quetzales' => 'required|numeric|min:0',
            'monto_dolares' => 'required|numeric|min:0',
            'tipo_cambio' => 'required|numeric|min:0',
            'link_drive' => 'nullable|url|max:500',
            'archivo' => 'nullable|file|max:20480',
        ]);

        $idUsuario = session('user.id_usuario');

        if (!$idUsuario) {
            return redirect()
                ->route('login')
                ->with('error', 'Sesión expirada. Inicia sesión nuevamente.');
        }

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
            'monto' => $request->monto_quetzales,
            'monto_quetzales' => $request->monto_quetzales,
            'monto_dolares' => $request->monto_dolares,
            'tipo_cambio' => $request->tipo_cambio,
            'descripcion' => $request->descripcion,
            'archivo_path' => $archivoPath,
            'archivo_original' => $archivoOriginal,
            'archivo_mime' => $archivoMime,
            'link_drive' => $request->link_drive,
            'id_usuario' => $idUsuario,
            'accion' => 'Creado',
        ]);

        return redirect()
            ->route('finanzas.historial')
            ->with('success', 'Movimiento financiero guardado correctamente.');
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
            'Empresa',
            'Proveedor',
            'Monto Quetzales',
            'Monto Dólares',
            'Tipo Cambio',
            'Descripción',
            'Link Drive',
            'Archivo',
            'Usuario',
        ];

        foreach ($movimientos as $mov) {
            $data[] = [
                $mov->tipo_movimiento,
                $mov->tipo_documento,
                $mov->no_documento,
                Carbon::parse($mov->fecha_documento)->format('d/m/Y'),
                $mov->proyecto->nombre ?? '',
                $mov->subproyecto->nombre ?? '',
                $mov->rubro->nombre ?? '',
                $mov->empresa,
                $mov->proveedor,
                $mov->monto_quetzales ?? $mov->monto,
                $mov->monto_dolares,
                $mov->tipo_cambio,
                $mov->descripcion,
                $mov->link_drive,
                $mov->archivo_original,
                $mov->usuario->nombre ?? '',
            ];
        }

        return Excel::download(
            new ArrayExport($data),
            'historial_financiero.xlsx'
        );
    }

    public function edit($id)
    {
        if (session('user.id_rol') != 1) {
            abort(403);
        }

        $movimiento = MovimientoFinanciero::findOrFail($id);

        $proyectos = Proyecto::where('activo', 1)->orderBy('nombre')->get();
        $subproyectos = Subproyecto::where('activo', 1)->orderBy('nombre')->get();
        $rubros = Rubro::where('activo', 1)->orderBy('nombre')->get();

        return view('finanzas.edit', compact(
            'movimiento',
            'proyectos',
            'subproyectos',
            'rubros'
        ));
    }

    public function update(Request $request, $id)
    {
        if (session('user.id_rol') != 1) {
            abort(403);
        }

        $movimiento = MovimientoFinanciero::findOrFail($id);

        $data = $request->all();

        if ($request->filled('monto_quetzales')) {
            $data['monto'] = $request->monto_quetzales;
        }

        if ($request->hasFile('archivo')) {
            if ($movimiento->archivo_path &&
                Storage::disk('public')->exists($movimiento->archivo_path)) {
                Storage::disk('public')->delete($movimiento->archivo_path);
            }

            $archivo = $request->file('archivo');

            $data['archivo_path'] = $archivo->store('finanzas', 'public');
            $data['archivo_original'] = $archivo->getClientOriginalName();
            $data['archivo_mime'] = $archivo->getClientMimeType();
        }

        $movimiento->update($data);

        return redirect()
            ->route('finanzas.historial')
            ->with('success', 'Movimiento actualizado correctamente.');
    }

    public function destroy($id)
    {
        if (session('user.id_rol') != 1) {
            abort(403);
        }

        $movimiento = MovimientoFinanciero::findOrFail($id);

        if ($movimiento->archivo_path &&
            Storage::disk('public')->exists($movimiento->archivo_path)) {
            Storage::disk('public')->delete($movimiento->archivo_path);
        }

        $movimiento->delete();

        return redirect()
            ->route('finanzas.historial')
            ->with('success', 'Movimiento eliminado correctamente.');
    }
}
