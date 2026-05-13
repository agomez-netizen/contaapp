<?php

namespace App\Models;
use App\Models\Usuario;
use App\Models\Subproyecto;

use Illuminate\Database\Eloquent\Model;

class MovimientoFinanciero extends Model
{
    protected $table = 'movimientos_financieros';
    protected $primaryKey = 'id_movimiento';

    protected $fillable = [
        'tipo_movimiento',
        'oficina',
        'id_proyecto',
        'id_rubro',
        'tipo_documento',
        'no_documento',
        'fecha_documento',
        'empresa',
        'proveedor',
        'monto',
        'descripcion',
        'archivo_path',
        'archivo_original',
        'archivo_mime',
        'id_usuario',
        'accion',
        'id_subproyecto',
        'monto_quetzales',
        'monto_dolares',
        'tipo_cambio',
        'link_drive',
    ];


    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto', 'id_proyecto');
    }

    public function rubro()
    {
        return $this->belongsTo(Rubro::class, 'id_rubro', 'id_rubro');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function subproyecto()
    {
        return $this->belongsTo(Subproyecto::class, 'id_subproyecto', 'id_subproyecto');
    }
}
