<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
    protected $table = 'contactos';
    protected $primaryKey = 'id_contacto';
    public $timestamps = true;

    protected $fillable = [
        'id_proyecto',
        'tipo',
        'nombre',
        'telefono',
        'extension',
        'correo',
        'direccion',
        'nit',
        'motivo',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto', 'id_proyecto');
    }
}
