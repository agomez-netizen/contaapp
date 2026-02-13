<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'pacientes';
    protected $primaryKey = 'id_paciente';
    public $timestamps = true;

    protected $fillable = [
        'nombre','dpi','sexo','edad','prioridad','carnet',
        'telefono','correo','departamento','municipio',
        'tipo_consulta','tipo_operacion',
        'empresa','nombre_empresa',
        'referido_por','telefono_referente','tipo_contacto',
        'tipo_consulta_referente','descripcion'
    ];
}
