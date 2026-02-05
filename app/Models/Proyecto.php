<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    protected $table = 'proyectos';
    protected $primaryKey = 'id_proyecto';

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo',
    ];

    public function usuarios()
    {
        return $this->belongsToMany(
            Usuario::class,
            'proyecto_usuario',
            'id_proyecto',
            'id_usuario'
        )->withTimestamps();
    }

}
