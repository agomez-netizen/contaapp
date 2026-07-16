<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProyectoOrganizacion extends Model
{
    protected $table = 'proyectos_organizacion';

    protected $fillable = [
        'organizacion_id',
        'convocatoria_id',
        'proyecto_id',
        'compatibilidad',
        'estado_aplicacion',
        'fecha_aplicacion',
        'monto_solicitado',
        'probabilidad',
        'observaciones',
    ];

    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'organizacion_id');
    }

    public function convocatoria()
    {
        return $this->belongsTo(Convocatoria::class, 'convocatoria_id');
    }

    public function proyecto()
    {
        return $this->belongsTo(ProyectoAapos::class, 'proyecto_id');
    }
}
