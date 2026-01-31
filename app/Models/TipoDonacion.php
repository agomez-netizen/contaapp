<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDonacion extends Model
{
    protected $table = 'tipos_donacion';
    protected $primaryKey = 'id_tipo_donacion';

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];
}
