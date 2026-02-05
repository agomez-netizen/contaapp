<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellido',
        'usuario',
        'pass',
        'id_rol',
        'estado'
    ];

    protected $hidden = ['pass'];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    public function proyectos()
    {
        return $this->belongsToMany(
            Proyecto::class,
            'proyecto_usuario',
            'id_usuario',
            'id_proyecto'
        )->withTimestamps();
    }
}
