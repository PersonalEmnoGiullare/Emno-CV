<?php

namespace App\Models\Qr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QrDispositivo extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'qr_dispositivo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'privada_id',
        'clave',
        'direccion_ip'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        // Puedes agregar casts si necesitas
        // 'direccion_ip' => 'encrypted', // Opcional: si quieres encriptar la IP
    ];

    // Relación con la privada
    public function privada()
    {
        return $this->belongsTo(QrPrivada::class, 'privada_id');
    }

    /**
     * Scope para dispositivos activos (no eliminados)
     */
    public function scopeActivos($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Scope para buscar por clave
     */
    public function scopePorClave($query, $clave)
    {
        return $query->where('clave', $clave);
    }

    /**
     * Scope para buscar por dirección IP
     */
    public function scopePorIp($query, $ip)
    {
        return $query->where('direccion_ip', $ip);
    }

    /**
     * Verifica si el dispositivo está asignado a una vivienda
     */
    public function estaAsignado()
    {
        return !is_null($this->privada_id);
    }
}
