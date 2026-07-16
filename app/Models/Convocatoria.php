<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Convocatoria extends Model
{
    protected $table = 'convocatorias';

    protected $fillable = [
        'organizacion_id',
        'nombre',
        'tipo_apoyo',
        'fecha_apertura',
        'fecha_cierre',
        'monto_minimo',
        'monto_maximo',
        'moneda',
        'periodicidad',
        'areas_prioritarias',
        'requisitos_clave',
        'enlace',
        'estado',
        'alerta_7_dias',
        'alerta_enviada',
        'fecha_alerta_enviada',
        'correo_alerta'
    ];

    public function proyectosOrganizacion()
    {
        return $this->hasMany(ProyectoOrganizacion::class, 'convocatoria_id');
    }
}
