<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id_rol';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = true;
    const UPDATED_AT = null; // quítalo si sí tienes updated_at

    protected $fillable = ['nombre', 'descripcion'];

    // CLAVE: que el binding use id_rol
    public function getRouteKeyName()
    {
        return 'id_rol';
    }

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_rol', 'id_rol');
    }
}
