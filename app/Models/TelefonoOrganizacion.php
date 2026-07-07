<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelefonoOrganizacion extends Model
{
    protected $table = 'telefonos_organizacion';

    protected $fillable = [
        'organizacion_id',
        'tipo',
        'numero',
        'extension',
        'pais',
        'observaciones'
    ];

    public $timestamps = false;
}
