<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avance extends Model
{
    protected $table = 'avances';
    protected $primaryKey = 'id_avance';

    protected $fillable = [
        'id_proyecto',
        'descripcion',
        'fecha',
        'user_id',
    ];

    protected $casts = [
        'fecha' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto', 'id_proyecto');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id', 'id_usuario');
    }

    public function historial()
    {
        return $this->hasMany(HistorialAvance::class, 'id_avance', 'id_avance')
            ->orderBy('created_at', 'desc');
    }
}
