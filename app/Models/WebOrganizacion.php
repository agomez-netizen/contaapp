<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebOrganizacion extends Model
{
    protected $table = 'webs_organizacion';

    protected $fillable = [
        'organizacion_id',
        'tipo',
        'url',
        'descripcion',
        'activo'
    ];

    public $timestamps = false;
}
