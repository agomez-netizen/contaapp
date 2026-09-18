<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonacionDetalle extends Model
{
    protected $table = 'donacion_detalles';
    protected $primaryKey = 'id_detalle';
    protected $fillable = ['id_tipo_donacion', 'id_proyecto', 'descripcion', 'unidades', 'monto'];
    protected $casts = ['unidades' => 'integer', 'monto' => 'decimal:2'];

    public function donacion()
    {
        return $this->belongsTo(Donacion::class, 'id_donacion', 'id_donacion');
    }
}
