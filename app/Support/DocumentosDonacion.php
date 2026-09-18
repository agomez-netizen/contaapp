<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentosDonacion
{
    public static function validar(Request $request): void
    {
        $reglas = [];
        foreach (['osshp', 'sat'] as $tipo) {
            $reglas['documentos_' . $tipo] = ['nullable', 'array', 'max:5'];
            $reglas['documentos_' . $tipo . '.*'] = [
                'required', 'file', 'mimes:jpg,jpeg,png,webp,pdf',
                'mimetypes:image/jpeg,image/png,image/webp,application/pdf', 'max:10240',
            ];
        }
        $request->validate($reglas, [
            'documentos_osshp.max' => 'Puede adjuntar hasta 5 documentos OSSHP por guardado.',
            'documentos_sat.max' => 'Puede adjuntar hasta 5 documentos SAT por guardado.',
            'documentos_osshp.*.max' => 'Cada documento OSSHP debe pesar como máximo 10 MB.',
            'documentos_sat.*.max' => 'Cada documento SAT debe pesar como máximo 10 MB.',
            'documentos_osshp.*.mimes' => 'Use imágenes JPG, PNG, WEBP o documentos PDF.',
            'documentos_sat.*.mimes' => 'Use imágenes JPG, PNG, WEBP o documentos PDF.',
        ]);
    }

    // Call inside the donation transaction; keep paths for cleanup on rollback.
    public static function guardar($id, Request $request, array &$guardados): void
    {
        $extensiones = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'application/pdf' => 'pdf'];
        foreach (['osshp', 'sat'] as $tipo) {
            foreach ($request->file('documentos_' . $tipo, []) as $archivo) {
                $uuid = (string) Str::uuid();
                $mime = $archivo->getMimeType();
                abort_unless(isset($extensiones[$mime]), 422, 'Formato de documento no permitido.');
                $ruta = $archivo->storeAs('donaciones-documentos/' . $id, $uuid . '.' . $extensiones[$mime], 'local');
                if (!$ruta) {
                    throw new \RuntimeException('No se pudo guardar el documento.');
                }
                $guardados[] = $ruta;
                DB::table('donacion_documentos')->insert([
                    'id_documento' => $uuid,
                    'id_donacion' => $id,
                    'tipo' => $tipo,
                    'ruta' => $ruta,
                    'nombre' => mb_substr(basename(str_replace('\\', '/', $archivo->getClientOriginalName())), 0, 200),
                    'mime' => $mime,
                    'tamano' => $archivo->getSize(),
                    'created_at' => now(),
                ]);
            }
        }
    }

    public static function limpiar(array $rutas): void
    {
        if (!$rutas) return;
        try {
            Storage::disk('local')->delete($rutas);
        } catch (\Throwable $error) {
            report($error);
        }
    }

    public static function listar($id)
    {
        return DB::table('donacion_documentos')->where('id_donacion', $id)
            ->orderBy('created_at')->orderBy('id_documento')->get();
    }

    public static function abrir($id, string $uuid)
    {
        abort_unless(session('user'), 403);
        $documento = DB::table('donacion_documentos')
            ->where('id_donacion', $id)->where('id_documento', $uuid)->first();
        abort_if(!$documento, 404);
        $disk = Storage::disk('local');
        abort_unless($disk->exists($documento->ruta), 404, 'Documento no disponible.');
        return response()->file($disk->path($documento->ruta), [
            'Content-Type' => $documento->mime,
            'Content-Disposition' => 'inline',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store',
            'Content-Security-Policy' => "sandbox; default-src 'none'; img-src 'self' data:; style-src 'unsafe-inline'",
        ]);
    }
}
