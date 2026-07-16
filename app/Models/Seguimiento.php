<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seguimiento extends Model
{
    protected $table = 'seguimientos';

    protected $fillable = [
        'organizacion_id',
        'fecha',
        'tipo_contacto',
        'descripcion',
        'responsable',
        'proximo_seguimiento',
        'resultado',
    ];

    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'organizacion_id');
    }
}
