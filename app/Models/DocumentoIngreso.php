<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoIngreso extends Model
{
    protected $table = 'documentos_ingresos';
    protected $primaryKey = 'id';

    protected $fillable = [
        'oficina',
        'id_proyecto',
        'id_rubro',
        'tipo_documento',
        'fecha_documento',
        'no_documento',
        'serie',
        'empresa_nombre',
        'nit',
        'telefono',
        'direccion',
        'correo',
        'contacto',
        'descripcion',
        'monto',
        'descuento',
        'pagada',
        'archivo_path',
        'archivo_original',
        'archivo_mime',
        'user_id',
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
        // OJO: si tu PK de usuarios es id_usuario, esto está bien:
        return $this->belongsTo(Usuario::class, 'user_id', 'id_usuario');
    }
}
