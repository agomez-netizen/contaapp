<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Avance;
use App\Models\Proyecto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AvanceController extends Controller
{
    public function create()
    {
        $proyectos = Proyecto::orderBy('nombre')->get();
        return view('avances.create', compact('proyectos'));
    }

public function store(Request $request)
{
    $u = session('user'); // tu login
    $userId = $u['id_usuario'] ?? null;

    if (!$userId) {
        abort(403, 'No hay usuario en sesión');
    }

    $data = $request->validate([
        'id_proyecto' => ['required', 'integer', 'exists:proyectos,id_proyecto'],
        'descripcion' => ['required', 'string', 'min:3'],
    ]);

    Avance::create([
        'id_proyecto' => $data['id_proyecto'],
        'descripcion' => $data['descripcion'],
        'fecha'       => Carbon::today()->toDateString(),
        'user_id'     => $userId,
    ]);

    return redirect()->route('avances.create')->with('ok', 'Avance registrado');
}


public function byDate(Request $request)
{
    $proyectos = Proyecto::orderBy('nombre')->get();

    // filtros (opcional)
    $idProyecto = $request->input('id_proyecto');
    $desde = $request->input('desde');
    $hasta = $request->input('hasta');

    $q = Avance::query()
        ->with(['proyecto', 'usuario'])
        ->when($idProyecto, fn($qq) => $qq->where('id_proyecto', $idProyecto))
        ->when($desde, fn($qq) => $qq->whereDate('fecha', '>=', $desde))
        ->when($hasta, fn($qq) => $qq->whereDate('fecha', '<=', $hasta))
        ->orderBy('fecha', 'desc')
        ->orderBy('created_at', 'desc');

    $avances = $q->get();

    // agrupado por fecha (YYYY-MM-DD)
    $grouped = $avances->groupBy(fn($a) => Carbon::parse($a->fecha)->toDateString());

    return view('avances.by_date', compact('proyectos', 'grouped', 'idProyecto', 'desde', 'hasta'));
}

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => ['required', 'image', 'max:5120'],
        ]);

        $path = $request->file('file')->store('avances', 'public');

        return response()->json([
            'location' => asset('storage/' . $path),
        ]);
    }

    public function dashboard(Request $request)
    {
        $desde = $request->get('desde');
        $hasta = $request->get('hasta');

        $rows = DB::table('avances as a')
            ->join('proyectos as p', 'p.id_proyecto', '=', 'a.id_proyecto')
            ->when($desde, fn($q) => $q->whereDate('a.fecha', '>=', $desde))
            ->when($hasta, fn($q) => $q->whereDate('a.fecha', '<=', $hasta))
            ->select('p.nombre', DB::raw('COUNT(*) as total'))
            ->groupBy('p.nombre')
            ->orderByDesc('total')
            ->get();

        $labels = $rows->pluck('nombre')->values();
        $data   = $rows->pluck('total')->values();

        $totalAvances = $rows->sum('total');
        $topProyecto  = $rows->first();

        return view('avances.dashboard', compact(
            'labels',
            'data',
            'desde',
            'hasta',
            'totalAvances',
            'topProyecto'
        ));
    }
}
