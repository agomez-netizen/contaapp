<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Avance;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class AvanceController extends Controller
{
    public function create()
    {
        $proyectos = Proyecto::orderBy('nombre')->get();
        return view('avances.create', compact('proyectos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fecha' => ['required', 'date'],
            'id_proyecto' => ['required', 'integer', 'exists:proyectos,id_proyecto'],
            'descripcion' => ['required', 'string', 'min:5'],
        ], [
            'id_proyecto.required' => 'Selecciona un proyecto.',
            'descripcion.required' => 'Escribe una descripción del avance.',
        ]);

        Avance::create($data);

        return redirect()
            ->route('avances.create')
            ->with('success', 'Avance agregado ✅');
    }

    public function byDate(Request $request)
    {
        $proyectos = Proyecto::orderBy('nombre')->get();

        $idProyecto = $request->get('id_proyecto');
        $desde = $request->get('desde');
        $hasta = $request->get('hasta');

        $query = Avance::query()->with('proyecto')
            ->when($idProyecto, fn($q) => $q->where('id_proyecto', $idProyecto))
            ->when($desde, fn($q) => $q->whereDate('fecha', '>=', $desde))
            ->when($hasta, fn($q) => $q->whereDate('fecha', '<=', $hasta))
            ->orderBy('fecha', 'desc')
            ->orderBy('id_avance', 'desc');

        $avances = $query->paginate(15)->withQueryString();

        // Agrupar para la vista tipo timeline
        $agrupados = $avances->getCollection()->groupBy(fn($a) => $a->fecha);

        return view('avances.by_date', compact('proyectos','avances','agrupados','idProyecto','desde','hasta'));
    }


    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => ['required', 'image', 'max:5120'], // 5MB
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
            'labels','data','desde','hasta','totalAvances','topProyecto'
        ));
    }

}
