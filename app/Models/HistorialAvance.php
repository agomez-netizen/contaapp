<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialAvance extends Model
{
    protected $table = 'historial_avances';
    protected $primaryKey = 'id_historial';

    protected $fillable = [
        'id_avance',
        'descripcion_anterior',
        'descripcion_nueva',
        'fecha_anterior',
        'fecha_nueva',
        'id_proyecto_anterior',
        'id_proyecto_nuevo',
        'editado_por',
    ];

    public function editor()
    {
        return $this->belongsTo(Usuario::class, 'editado_por', 'id_usuario');
    }

    public function avance()
    {
        return $this->belongsTo(Avance::class, 'id_avance', 'id_avance');
    }
}
