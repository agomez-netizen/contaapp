<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subproyecto extends Model
{
    protected $table = 'subproyectos';
    protected $primaryKey = 'id_subproyecto';

    protected $fillable = [
        'id_proyecto',
        'nombre',
        'descripcion',
        'activo',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto', 'id_proyecto');
    }
}
