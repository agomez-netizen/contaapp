<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Avance;
use App\Models\Proyecto;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\HistorialAvance;


class AvanceController extends Controller
{
    public function create()
    {
        $u = session('user');
        $userId = $u['id_usuario'] ?? null;

        if (!$userId) {
            abort(403, 'No hay usuario en sesión');
        }

        $rolName = strtoupper(trim($u['rol'] ?? $u['nombre_rol'] ?? ''));
        $rolId   = (int)($u['id_rol'] ?? 0);
        $isAdmin = ($rolId === 1) || ($rolName === 'ADMIN');

        $proyectos = $isAdmin
            ? Proyecto::where('activo', 1)->orderBy('nombre')->get()
            : Proyecto::where('activo', 1)
                ->whereHas('usuarios', function ($q) use ($userId) {
                    $q->where('usuarios.id_usuario', $userId);
                })
                ->orderBy('nombre')
                ->get();

        return view('avances.create', compact('proyectos'));
    }

    public function store(Request $request)
    {
        $u = session('user');
        $userId = $u['id_usuario'] ?? null;

        if (!$userId) {
            abort(403, 'No hay usuario en sesión');
        }

        $rolName = strtoupper(trim($u['rol'] ?? $u['nombre_rol'] ?? ''));
        $rolId   = (int)($u['id_rol'] ?? 0);
        $isAdmin = ($rolId === 1) || ($rolName === 'ADMIN');

        $data = $request->validate([
            'id_proyecto' => ['required', 'integer'],
            'descripcion' => ['required', 'string'],
            'fecha'       => ['nullable', 'date'],
        ]);

        if (!$isAdmin) {
            $allowed = Proyecto::where('id_proyecto', $data['id_proyecto'])
                ->whereHas('usuarios', fn($q) => $q->where('usuarios.id_usuario', $userId))
                ->exists();

            if (!$allowed) {
                abort(403, 'Proyecto no asignado a tu usuario');
            }
        }

        $fechaInput = $data['fecha'] ?? null;
        $fecha = $fechaInput
            ? Carbon::parse($fechaInput)->toDateString()
            : Carbon::now()->toDateString();

        Avance::create([
            'id_proyecto' => (int)$data['id_proyecto'],
            'descripcion' => $data['descripcion'],
            'fecha'       => $fecha,
            'user_id'     => (int)$userId,
        ]);

        return redirect()
            ->route('avances.create')
            ->with('success', '✅ Avance guardado correctamente.');
    }

public function edit($id)
{
    $u = session('user');
    $userId = $u['id_usuario'] ?? null;

    if (!$userId) {
        abort(403, 'No hay usuario en sesión');
    }

    $rolName = strtoupper(trim($u['rol'] ?? $u['nombre_rol'] ?? ''));
    $rolId   = (int)($u['id_rol'] ?? 0);
    $isAdmin = ($rolId === 1) || ($rolName === 'ADMIN');

    $avance = Avance::with(['proyecto', 'usuario', 'historial.editor'])->findOrFail($id);

    if (!$isAdmin) {
        $allowedProject = Proyecto::where('id_proyecto', $avance->id_proyecto)
            ->whereHas('usuarios', fn($q) => $q->where('usuarios.id_usuario', $userId))
            ->exists();

        $isOwner = (int)$avance->user_id === (int)$userId;

        if (!$allowedProject || !$isOwner) {
            abort(403, 'No tienes permiso para editar este avance');
        }
    }

    $proyectos = $isAdmin
        ? Proyecto::where('activo', 1)->orderBy('nombre')->get()
        : Proyecto::where('activo', 1)
            ->whereHas('usuarios', function ($q) use ($userId) {
                $q->where('usuarios.id_usuario', $userId);
            })
            ->orderBy('nombre')
            ->get();

    return view('avances.edit', compact('avance', 'proyectos'));
}

public function update(Request $request, $id)
{
    $u = session('user');
    $userId = $u['id_usuario'] ?? null;

    if (!$userId) {
        abort(403, 'No hay usuario en sesión');
    }

    $rolName = strtoupper(trim($u['rol'] ?? $u['nombre_rol'] ?? ''));
    $rolId   = (int)($u['id_rol'] ?? 0);
    $isAdmin = ($rolId === 1) || ($rolName === 'ADMIN');

    $avance = Avance::findOrFail($id);

    if (!$isAdmin) {
        $allowedProjectCurrent = Proyecto::where('id_proyecto', $avance->id_proyecto)
            ->whereHas('usuarios', fn($q) => $q->where('usuarios.id_usuario', $userId))
            ->exists();

        $isOwner = (int)$avance->user_id === (int)$userId;

        if (!$allowedProjectCurrent || !$isOwner) {
            abort(403, 'No tienes permiso para editar este avance');
        }
    }

    $data = $request->validate([
        'id_proyecto' => ['required', 'integer'],
        'descripcion' => ['required', 'string'],
        'fecha'       => ['required', 'date'],
    ]);

    if (!$isAdmin) {
        $allowedNewProject = Proyecto::where('id_proyecto', $data['id_proyecto'])
            ->whereHas('usuarios', fn($q) => $q->where('usuarios.id_usuario', $userId))
            ->exists();

        if (!$allowedNewProject) {
            abort(403, 'Proyecto no asignado a tu usuario');
        }
    }

    $descripcionNuevaHtml = nl2br(e($data['descripcion']));

    $huboCambios = (
        (int)$avance->id_proyecto !== (int)$data['id_proyecto'] ||
        (string)$avance->fecha->format('Y-m-d') !== (string)$data['fecha'] ||
        trim(strip_tags($avance->descripcion)) !== trim($data['descripcion'])
    );

    if ($huboCambios) {
        HistorialAvance::create([
            'id_avance' => $avance->id_avance,
            'descripcion_anterior' => $avance->descripcion,
            'descripcion_nueva' => $descripcionNuevaHtml,
            'fecha_anterior' => optional($avance->fecha)->format('Y-m-d'),
            'fecha_nueva' => $data['fecha'],
            'id_proyecto_anterior' => $avance->id_proyecto,
            'id_proyecto_nuevo' => $data['id_proyecto'],
            'editado_por' => $userId,
        ]);
    }

    $avance->update([
        'id_proyecto' => (int)$data['id_proyecto'],
        'descripcion' => $descripcionNuevaHtml,
        'fecha'       => Carbon::parse($data['fecha'])->toDateString(),
    ]);

    return redirect()
        ->route('avances.edit', $avance->id_avance)
        ->with('success', '✅ Avance actualizado correctamente.');
}


    public function byDate(Request $request)
    {
        $u = session('user');
        $userId = $u['id_usuario'] ?? null;

        if (!$userId) {
            abort(403, 'No hay usuario en sesión');
        }

        $rolName = strtoupper(trim($u['rol'] ?? $u['nombre_rol'] ?? ''));
        $rolId   = (int)($u['id_rol'] ?? 0);
        $isAdmin = ($rolId === 1) || ($rolName === 'ADMIN');

        $proyectos = $isAdmin
            ? Proyecto::orderBy('nombre')->get()
            : Proyecto::whereHas('usuarios', fn($q) => $q->where('usuarios.id_usuario', $userId))
                ->orderBy('nombre')
                ->get();

        $idProyecto = $request->input('id_proyecto');
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');

        $q = Avance::query()
            ->with(['proyecto', 'usuario'])
            ->when(!$isAdmin, function ($qq) use ($userId) {
                $qq->whereHas('proyecto.usuarios', fn($q2) => $q2->where('usuarios.id_usuario', $userId));
            })
            ->when($idProyecto, fn($qq) => $qq->where('id_proyecto', $idProyecto))
            ->when($desde, fn($qq) => $qq->whereDate('fecha', '>=', $desde))
            ->when($hasta, fn($qq) => $qq->whereDate('fecha', '<=', $hasta))
            ->orderBy('fecha', 'desc')
            ->orderBy('created_at', 'desc');

        $avances = $q->get();
        $grouped = $avances->groupBy(fn($a) => Carbon::parse($a->fecha)->toDateString());

        return view('avances.by_date', compact('proyectos', 'grouped', 'idProyecto', 'desde', 'hasta', 'isAdmin', 'userId'));
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
        $desde = $request->desde;
        $hasta = $request->hasta;

        $totalAvances = DB::table('avances')
            ->when($desde, fn($q) => $q->whereDate('fecha', '>=', $desde))
            ->when($hasta, fn($q) => $q->whereDate('fecha', '<=', $hasta))
            ->count();

        $rows = DB::table('avances as a')
            ->join('proyectos as p', 'p.id_proyecto', '=', 'a.id_proyecto')
            ->when($desde, fn($q) => $q->whereDate('a.fecha', '>=', $desde))
            ->when($hasta, fn($q) => $q->whereDate('a.fecha', '<=', $hasta))
            ->select('p.nombre as proyecto', DB::raw('COUNT(*) as total'))
            ->groupBy('p.nombre')
            ->orderByDesc('total')
            ->get();

        $labels = $rows->pluck('proyecto')->values();
        $data   = $rows->pluck('total')->values();

        $topProyecto = $rows->first();

        $userRows = DB::table('avances as a')
            ->join('usuarios as u', 'u.id_usuario', '=', 'a.user_id')
            ->when($desde, fn($q) => $q->whereDate('a.fecha', '>=', $desde))
            ->when($hasta, fn($q) => $q->whereDate('a.fecha', '<=', $hasta))
            ->select(
                DB::raw("CONCAT(u.nombre, ' ', u.apellido) as usuario"),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('u.nombre', 'u.apellido')
            ->orderByDesc('total')
            ->get();

        $userLabels = $userRows->pluck('usuario')->values();
        $userData   = $userRows->pluck('total')->values();

        $topUsuario = $userRows->first();

        return view('avances.dashboard', compact(
            'labels',
            'data',
            'desde',
            'hasta',
            'totalAvances',
            'topProyecto',
            'userLabels',
            'userData',
            'topUsuario'
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
                $a->proyecto->nombre ?? '—',
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
        if (!$html) {
            return '';
        }

        $text = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = strip_tags($text);
        $text = preg_replace('/[ \t]+/', ' ', $text);
        $text = preg_replace("/\r\n|\r|\n/", "\n", $text);

        return trim($text);
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
            'proyectos',
            'grouped',
            'idProyecto',
            'desde',
            'hasta'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('avances.pdf');
    }
}

