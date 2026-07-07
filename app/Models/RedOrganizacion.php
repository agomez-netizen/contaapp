<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedOrganizacion extends Model
{
    protected $table = 'redes_organizacion';

    protected $fillable = [
        'organizacion_id',
        'red_social',
        'url',
        'usuario',
        'notas'
    ];

    public $timestamps = false;
}
