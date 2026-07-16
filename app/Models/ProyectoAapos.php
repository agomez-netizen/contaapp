<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProyectoAapos extends Model
{
    protected $table = 'proyectos_aapos';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'categoria',
        'presupuesto_estimado',
        'moneda',
        'beneficiarios',
        'estado',
        'documentacion_lista',
    ];

    protected $casts = [
        'presupuesto_estimado' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function proyectosOrganizacion(): HasMany
    {
        return $this->hasMany(
            ProyectoOrganizacion::class,
            'proyecto_id'
        );
    }
}
