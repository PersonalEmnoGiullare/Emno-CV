<?php

namespace App\Models\RepMetro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepMetroEstacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rep_metro_estaciones';
    protected $fillable = ['nombre', 'ubicacion', 'activa'];
    protected $casts = ['activa' => 'boolean'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function accesos()
    {
        return $this->hasMany(RepMetroAcceso::class, 'id_estacion');
    }

    public function frecuenciasAcceso()
    {
        return $this->hasMany(RepMetroFrecuenciaAcceso::class, 'id_estacion');
    }
}
