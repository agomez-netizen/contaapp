<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalidadVidaItem extends Model
{
    protected $table = 'calidadvida_items';

    protected $fillable = [
        'rubro',
        'monto',
        'ejecutado',
        'en_proceso',
        'pendiente',
        'no_documento',
        'descripcion'
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'ejecutado' => 'decimal:2',
        'en_proceso' => 'decimal:2',
        'pendiente' => 'decimal:2',
    ];
}
