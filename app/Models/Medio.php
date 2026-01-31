<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medio extends Model
{
    protected $table = 'medios';
    protected $primaryKey = 'id_medio';

    protected $fillable = [
        'medio','tipo','nombre','nombre_completo','telefono','contacto_cargo',
        'celular_contacto','direccion','email','website','redsocial','observaciones'
    ];
}
