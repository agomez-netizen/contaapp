<?php

namespace App\Http\Controllers;

use App\Models\CalidadVidaItem;
use Illuminate\Http\Request;

class CalidadVidaController extends Controller
{
    public function index()
    {
        $items = CalidadVidaItem::orderBy('id')->get();

        $totMonto     = $items->sum('monto');
        $totEjecutado = $items->sum('ejecutado');
        $totProceso   = $items->sum('en_proceso');
        $totPendiente = $items->sum('pendiente');

        // Ajustable: si quieres una cifra fija, cámbiala aquí.
        $donacionInicial = $totMonto;

        return view('proyectosaapos.calidadvida', compact(
            'items',
            'totMonto','totEjecutado','totProceso','totPendiente',
            'donacionInicial'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'rubro'      => ['required','string','max:150'],
            'monto'      => ['required','numeric','min:0'],
            'ejecutado'  => ['nullable','numeric','min:0'],
            'en_proceso' => ['nullable','numeric','min:0'],
            'pendiente'  => ['nullable','numeric','min:0'],
            'no_documento' => ['nullable','string','max:50'],
            'descripcion'  => ['nullable','string'],
        ]);

        $data['ejecutado']  = $data['ejecutado']  ?? 0;
        $data['en_proceso'] = $data['en_proceso'] ?? 0;
        $data['pendiente']  = $data['pendiente']  ?? 0;

        CalidadVidaItem::create($data);

        return redirect()->route('calidadvida.index')->with('ok', 'Rubro agregado.');
    }

    public function update(Request $request, CalidadVidaItem $item)
    {
        $data = $request->validate([
            'rubro'        => ['required','string','max:150'],
            'monto'        => ['required','numeric','min:0'],
            'ejecutado'    => ['nullable','numeric','min:0'],
            'en_proceso'   => ['nullable','numeric','min:0'],
            'pendiente'    => ['nullable','numeric','min:0'],
            'no_documento' => ['nullable','string','max:50'],
            'descripcion'  => ['nullable','string'],
        ]);

        $data['ejecutado']  = $data['ejecutado']  ?? 0;
        $data['en_proceso'] = $data['en_proceso'] ?? 0;
        $data['pendiente']  = $data['pendiente']  ?? 0;

        $item->update($data);

        return redirect()->route('calidadvida.index')->with('ok', 'Rubro actualizado.');
    }

    public function destroy(CalidadVidaItem $item)
    {
        $item->delete();
        return redirect()->route('calidadvida.index')->with('ok', 'Rubro eliminado.');
    }
}
