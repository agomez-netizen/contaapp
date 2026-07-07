<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactoOrganizacion extends Model
{
    protected $table = 'contactos_organizacion';

    protected $fillable = [
        'organizacion_id',
        'nombre',
        'cargo',
        'correo',
        'telefono',
        'whatsapp',
        'idioma',
        'medio_preferido',
        'notas'
    ];

    public $timestamps = false;
}
