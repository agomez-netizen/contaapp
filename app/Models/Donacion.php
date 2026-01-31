<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donacion extends Model
{
    protected $table = 'donaciones';
    protected $primaryKey = 'id_donacion';

    protected $fillable = [
        'id_usuario',
        'fecha_despachada',
        'empresa',
        'nit',
        'contacto',
        'telefono',
        'correo',
        'unidades',
        'descripcion',
        'valor_total_donacion',

        'id_ubicacion',
        'fecha_recibe',
        'quien_recibe',
        'id_tipo_donacion',
        'unidades_entrega',
        'persona_gestiono',

        'precio_mercado_unidad',
        'total_mercado',
        'referencia_mercado',
        'costo_logistica',
        'descripcion_logistica',

        'id_proyecto',
        'impacto_personas',
        'comentarios',

        'recibo_empresa',
        'ref_osshp',
        'fecha_ref_osshp',
        'ref_sat',
        'fecha_ref_sat',
    ];

    protected $casts = [
        'bloqueado' => 'boolean',
    ];


    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
