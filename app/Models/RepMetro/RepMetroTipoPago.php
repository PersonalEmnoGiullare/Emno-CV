<?php

namespace App\Models\RepMetro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepMetroTipoPago extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rep_metro_tipos_pago';
    protected $fillable = ['nombre', 'descripcion'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function accesos()
    {
        return $this->hasMany(RepMetroAcceso::class, 'id_tipo_pago');
    }

    public function frecuenciasAcceso()
    {
        return $this->hasMany(RepMetroFrecuenciaAcceso::class, 'id_tipo_pago');
    }
}
