<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avance extends Model
{
    protected $table = 'avances';
    protected $primaryKey = 'id_avance';

    protected $fillable = [
        'id_proyecto',
        'fecha',
        'descripcion',
    ];

    // Relación (ajusta el nombre del modelo Proyecto si es distinto)
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto', 'id_proyecto');
    }
}
