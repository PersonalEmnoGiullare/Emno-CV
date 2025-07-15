<?php

namespace App\Models\RepMetro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepMetroAcceso extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rep_metro_accesos';
    protected $fillable = ['fecha_hora', 'id_estacion', 'id_tipo_pago', 'id_tarifa'];
    protected $casts = ['fecha_hora' => 'datetime'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function estacion()
    {
        return $this->belongsTo(RepMetroEstacion::class, 'id_estacion');
    }

    public function tipoPago()
    {
        return $this->belongsTo(RepMetroTipoPago::class, 'id_tipo_pago');
    }

    public function tarifa()
    {
        return $this->belongsTo(RepMetroTarifa::class, 'id_tarifa');
    }
}
