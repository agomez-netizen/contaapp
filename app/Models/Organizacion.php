<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organizacion extends Model
{
    protected $table = 'organizaciones';

    protected $fillable = [
        'nombre',
        'tipo_organizacion',
        'pais',
        'direccion',
        'correo_general',
        'area_apoyo',
        'enfoque_geografico',
        'idioma_comunicacion',
        'descripcion',
        'monto_estimado',
        'prioridad',
        'estado'
    ];

    public function contactos()
    {
        return $this->hasMany(ContactoOrganizacion::class, 'organizacion_id');
    }

    public function telefonos()
    {
        return $this->hasMany(TelefonoOrganizacion::class, 'organizacion_id');
    }

    public function webs()
    {
        return $this->hasMany(WebOrganizacion::class, 'organizacion_id');
    }

    public function redes()
    {
        return $this->hasMany(RedOrganizacion::class, 'organizacion_id');
    }
}
