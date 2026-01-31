<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'pacientes';
    protected $primaryKey = 'id_paciente';

    protected $fillable = [
        'nombre',
        'dpi',
        'sexo',
        'edad',
        'carnet',
        'telefono',
        'correo',
        'departamento',
        'municipio',
        'tipo_consulta',
        'empresa',
        'nombre_empresa',
        'referido_por',
        'telefono_referente',
        'tipo_contacto',
        'tipo_consulta_referente',
        'descripcion',
    ];
}
