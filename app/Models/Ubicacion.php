<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    protected $table = 'ubicaciones';
    protected $primaryKey = 'id_ubicacion';

    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'activo',
    ];

    // Para que Route Model Binding use id_ubicacion
    public function getRouteKeyName()
    {
        return 'id_ubicacion';
    }

    // Cast para que activo sea boolean
    protected $casts = [
        'activo' => 'boolean',
    ];
}
