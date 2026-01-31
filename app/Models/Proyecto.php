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
}
