<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Avance;
use App\Models\Proyecto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;



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
        'descripcion' => ['required', 'string', 'min:5'],
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



public function exportExcel(Request $request)
{
    $desde      = $request->input('desde');
    $hasta      = $request->input('hasta');
    $idProyecto = $request->input('id_proyecto');

    $avances = Avance::with(['proyecto', 'usuario'])
        ->when($idProyecto, fn($q) => $q->where('id_proyecto', $idProyecto))
        ->when($desde, fn($q) => $q->whereDate('fecha', '>=', $desde))
        ->when($hasta, fn($q) => $q->whereDate('fecha', '<=', $hasta))
        ->orderBy('fecha', 'desc')
        ->get();

    $rows = [[
        'Fecha',
        'Proyecto',
        'Descripción',
        'Nombre',
        'Apellido',
        'Hora',
    ]];

    foreach ($avances as $a) {
        $rows[] = [
            $a->fecha,
            $a->proyecto->nombre ?? '—' ,
            $this->plainText($a->descripcion),
            $a->usuario->nombre ?? 'Usuario eliminado',
            $a->usuario->apellido ?? 'Usuario eliminado',
            $a->created_at ? $a->created_at->format('H:i') : '',
        ];
    }

    return Excel::download(new \App\Exports\ArrayExport($rows), 'avances.xlsx');

}

   private function plainText(?string $html): string
    {
        if (!$html) return '';

        // 1) decode &nbsp; &amp; etc.
        $text = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // 2) quita tags
        $text = strip_tags($text);

        // 3) normaliza espacios
        $text = preg_replace('/[ \t]+/', ' ', $text);       // espacios repetidos
        $text = preg_replace("/\r\n|\r|\n/", "\n", $text);  // saltos normalizados
        $text = trim($text);

        return $text;
    }

    public function exportPdf(Request $request)
{
    $proyectos = Proyecto::orderBy('nombre')->get();

    $idProyecto = $request->input('id_proyecto');
    $desde = $request->input('desde');
    $hasta = $request->input('hasta');

    $avances = Avance::query()
        ->with(['proyecto', 'usuario'])
        ->when($idProyecto, fn($qq) => $qq->where('id_proyecto', $idProyecto))
        ->when($desde, fn($qq) => $qq->whereDate('fecha', '>=', $desde))
        ->when($hasta, fn($qq) => $qq->whereDate('fecha', '<=', $hasta))
        ->orderBy('fecha', 'desc')
        ->orderBy('created_at', 'desc')
        ->get();

    $grouped = $avances->groupBy(fn($a) => Carbon::parse($a->fecha)->toDateString());

    $pdf = Pdf::loadView('avances.pdf_by_date', compact(
        'proyectos', 'grouped', 'idProyecto', 'desde', 'hasta'
    ))->setPaper('a4', 'portrait');

    return $pdf->download('avances.pdf');
}



}
