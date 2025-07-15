<?php

namespace App\Models\RepMetro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepMetroTarifa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rep_metro_tarifas';
    protected $fillable = ['importe', 'fecha_inicio', 'fecha_fin'];
    protected $casts = [
        'importe' => 'decimal:2',
        'fecha_inicio' => 'date:Y-m-d',
        'fecha_fin' => 'date:Y-m-d'
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function accesos()
    {
        return $this->hasMany(RepMetroAcceso::class, 'id_tarifa');
    }

    public function frecuenciasAcceso()
    {
        return $this->hasMany(RepMetroFrecuenciaAcceso::class, 'id_tarifa');
    }
}
