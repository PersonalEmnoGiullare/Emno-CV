<?php

namespace App\Models\RepMetro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class RepMetroFrecuenciaAcceso extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rep_metro_frecuencias_acceso';
    protected $fillable = ['id_estacion', 'id_tipo_pago', 'id_tarifa', 'periodo', 'cantidad'];
    protected $casts = [
        'periodo' => 'date:Y-m-d',
        'cantidad' => 'integer'
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    // Asegura que periodo siempre sea el primer día del mes
    public function setPeriodoAttribute($value)
    {
        $this->attributes['periodo'] = Carbon::parse($value)->startOfMonth();
    }

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

    // Accesor para obtener el periodo formateado (MM/YYYY)
    public function getPeriodoFormateadoAttribute()
    {
        return $this->periodo->format('m/Y');
    }
}
