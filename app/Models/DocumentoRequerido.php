<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoRequerido extends Model
{
    protected $table = 'documentos_requeridos';

    protected $fillable = [
        'organizacion_id',
        'documento',
        'disponible',
        'responsable',
        'fecha_actualizacion',
        'observaciones',
    ];

    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'organizacion_id');
    }
}
