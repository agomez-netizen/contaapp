<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rubro extends Model
{
    protected $table = 'rubros';
    protected $primaryKey = 'id_rubro';

    protected $fillable = ['nombre', 'activo'];

    public function documentos()
    {
        return $this->hasMany(DocumentoIngreso::class, 'id_rubro', 'id_rubro');
    }
}
