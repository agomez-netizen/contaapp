<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentoIngreso;
use App\Models\Proyecto;
use App\Models\Rubro;
use App\Exports\ArrayExport;
use Maatwebsite\Excel\Facades\Excel;

class OficinaAntiguaController extends Controller
{
    private const OFICINA = 'ANTIGUA';

    private function baseQuery(Request $request)
    {
        $q        = trim((string)$request->get('q', ''));
        $tipo     = trim((string)$request->get('tipo', ''));
        $proyecto = trim((string)$request->get('proyecto', ''));
        $rubro    = trim((string)$request->get('rubro', ''));
        $pagada   = trim((string)$request->get('pagada', ''));
        $desde    = trim((string)$request->get('desde', ''));
        $hasta    = trim((string)$request->get('hasta', ''));

        $query = DocumentoIngreso::with(['proyecto', 'rubro', 'usuario'])
            ->where('oficina', self::OFICINA);

        if ($q !== '') {
            $query->where(function ($qq) use ($q) {
                $qq->where('empresa_nombre', 'like', "%{$q}%")
                   ->orWhere('no_documento', 'like', "%{$q}%")
                   ->orWhere('serie', 'like', "%{$q}%")
                   ->orWhere('no_documento_pago', 'like', "%{$q}%");
            });
        }

        if ($tipo !== '') {
            $query->where('tipo_documento', $tipo);
        }

        if ($proyecto !== '' && ctype_digit($proyecto)) {
            $query->where('id_proyecto', (int)$proyecto);
        }

        if ($rubro !== '' && ctype_digit($rubro)) {
            $query->where('id_rubro', (int)$rubro);
        }

        if ($pagada !== '' && ($pagada === '0' || $pagada === '1')) {
            $query->where('pagada', (int)$pagada);
        }

        if ($desde !== '') {
            $query->whereDate('fecha_documento', '>=', $desde);
        }

        if ($hasta !== '') {
            $query->whereDate('fecha_documento', '<=', $hasta);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $rows = $this->baseQuery($request)
            ->orderByDesc('fecha_documento')
            ->paginate(15)
            ->withQueryString();

        $proyectos = Proyecto::orderBy('nombre')->get();
        $rubros = Rubro::where('activo', 1)->orderBy('nombre')->get();

        $filters = $request->all();

        return view('oficina.antigua.index', compact('rows', 'proyectos', 'rubros', 'filters'));
    }

    public function exportExcel(Request $request)
    {
        $rows = $this->baseQuery($request)
            ->orderByDesc('fecha_documento')
            ->get();

        $data = [];
        $data[] = [
            'Movimiento','Fecha','Usuario','Proyecto','Rubro',
            'Monto','Descuento','Pagada',
            'No Documento','Empresa','NIT',
            'No Documento Pago','Fecha Pago',
            'Descripción'
        ];

        foreach ($rows as $r) {
            $data[] = [
                $r->tipo_documento,
                $r->fecha_documento,
                $r->usuario->nombre ?? '',
                $r->proyecto->nombre ?? '',
                $r->rubro->nombre ?? '',
                (float)$r->monto,
                (float)$r->descuento,
                $r->pagada ? 'SI' : 'NO',
                $r->no_documento ?? '',
                $r->empresa_nombre ?? '',
                $r->nit ?? '',
                $r->no_documento_pago ?? '',
                $r->fecha_pago ?? '',
                $r->descripcion ?? '',
            ];
        }

        $filename = 'oficina_antigua_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new ArrayExport($data), $filename);
    }

    public function create()
    {
        $proyectos = Proyecto::orderBy('nombre')->get();
        $rubros = Rubro::where('activo', 1)->orderBy('nombre')->get();
        return view('oficina.antigua.create', compact('proyectos', 'rubros'));
    }

    public function store(Request $request)
    {
        $u = session('user');
        $userId = $u['id_usuario'] ?? null;

        $data = $request->validate([
            'tipo_documento'  => ['required', 'in:FACTURA,COTIZACION'],
            'id_proyecto'     => ['required', 'integer'],
            'id_rubro'        => ['nullable', 'integer'],

            'fecha_documento' => ['required', 'date'],
            'no_documento'    => ['nullable', 'string', 'max:50'],
            'serie'           => ['nullable', 'string', 'max:50'],

            'empresa_nombre'  => ['nullable', 'string', 'max:150'],
            'nit'             => ['nullable', 'string', 'max:50'],
            'telefono'        => ['nullable', 'string', 'max:30'],
            'direccion'       => ['nullable', 'string', 'max:255'],
            'correo'          => ['nullable', 'string', 'max:120'],
            'contacto'        => ['nullable', 'string', 'max:120'],

            'descripcion'     => ['nullable', 'string'],

            'monto'           => ['required', 'numeric', 'min:0'],
            'descuento'       => ['nullable', 'numeric', 'min:0'],
            'pagada'          => ['nullable', 'boolean'],

            // NUEVOS CAMPOS
            'no_documento_pago' => ['nullable', 'string', 'max:100'],
            'fecha_pago'        => ['nullable', 'date'],

            'archivo' => ['nullable', 'file', 'max:5120', 'mimes:pdf,jpg,jpeg,png'],
        ]);

        $row = new DocumentoIngreso();
        $row->fill($data);
        $row->oficina = self::OFICINA;
        $row->user_id = $userId;
        $row->pagada  = (int)$request->has('pagada');

        [$path, $original, $mime] = $this->handleUpload($request);
        $row->archivo_path = $path;
        $row->archivo_original = $original;
        $row->archivo_mime = $mime;

        $row->save();

        return redirect()->route('oficina.antigua.index')->with('success', 'Registro guardado.');
    }

    public function edit($id)
    {
        $row = DocumentoIngreso::where('oficina', self::OFICINA)->findOrFail($id);
        $proyectos = Proyecto::orderBy('nombre')->get();
        $rubros = Rubro::where('activo', 1)->orderBy('nombre')->get();
        return view('oficina.antigua.edit', compact('row', 'proyectos', 'rubros'));
    }

    public function update(Request $request, $id)
    {
        $row = DocumentoIngreso::where('oficina', self::OFICINA)->findOrFail($id);

        $data = $request->validate([
            'tipo_documento'  => ['required', 'in:FACTURA,COTIZACION'],
            'id_proyecto'     => ['required', 'integer'],
            'id_rubro'        => ['nullable', 'integer'],

            'fecha_documento' => ['required', 'date'],
            'no_documento'    => ['nullable', 'string', 'max:50'],
            'serie'           => ['nullable', 'string', 'max:50'],

            'empresa_nombre'  => ['nullable', 'string', 'max:150'],
            'nit'             => ['nullable', 'string', 'max:50'],
            'telefono'        => ['nullable', 'string', 'max:30'],
            'direccion'       => ['nullable', 'string', 'max:255'],
            'correo'          => ['nullable', 'string', 'max:120'],
            'contacto'        => ['nullable', 'string', 'max:120'],

            'descripcion'     => ['nullable', 'string'],

            'monto'           => ['required', 'numeric', 'min:0'],
            'descuento'       => ['nullable', 'numeric', 'min:0'],
            'pagada'          => ['nullable', 'boolean'],

            // NUEVOS CAMPOS
            'no_documento_pago' => ['nullable', 'string', 'max:100'],
            'fecha_pago'        => ['nullable', 'date'],

            'archivo' => ['nullable', 'file', 'max:5120', 'mimes:pdf,jpg,jpeg,png'],
        ]);

        $row->fill($data);
        $row->pagada = (int)$request->has('pagada');

        if ($request->hasFile('archivo')) {
            $this->deleteOldFile($row->archivo_path);
            [$path, $original, $mime] = $this->handleUpload($request);
            $row->archivo_path = $path;
            $row->archivo_original = $original;
            $row->archivo_mime = $mime;
        }

        $row->save();

        return redirect()->route('oficina.antigua.index')->with('success', 'Registro actualizado.');
    }

    public function destroy($id)
    {
        $row = DocumentoIngreso::where('oficina', self::OFICINA)->findOrFail($id);
        $this->deleteOldFile($row->archivo_path);
        $row->delete();

        return redirect()->route('oficina.antigua.index')->with('success', 'Registro eliminado.');
    }

    private function handleUpload(Request $request): array
    {
        if (!$request->hasFile('archivo')) {
            return [null, null, null];
        }

        $file = $request->file('archivo');

        if (!$file->isValid()) {
            return [null, null, null];
        }

        $folder = public_path('uploads/oficina');
        if (!is_dir($folder)) {
            @mkdir($folder, 0755, true);
        }

        $original = $file->getClientOriginalName();
        $mime = $file->getClientMimeType() ?: $file->getMimeType();
        $ext = $file->getClientOriginalExtension() ?: 'bin';

        $safeName = 'antigua_' . date('Ymd_His') . '_' . uniqid() . '.' . $ext;
        $file->move($folder, $safeName);

        $path = 'uploads/oficina/' . $safeName;

        return [$path, $original, $mime];
    }

    private function deleteOldFile(?string $path): void
    {
        if (!$path) return;
        $full = public_path($path);
        if (is_file($full)) {
            @unlink($full);
        }
    }

    public function show($id)
    {
        $row = DocumentoIngreso::with(['proyecto', 'rubro', 'usuario'])
            ->where('oficina', self::OFICINA)
            ->findOrFail($id);

        return view('oficina.antigua.show', compact('row'));
    }
}
