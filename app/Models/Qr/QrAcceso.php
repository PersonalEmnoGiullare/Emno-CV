<?php

namespace App\Models\Qr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QrAcceso extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'qr_accesos';

    protected $fillable = [
        'codigo_qr_id',
        'dispositivo_id',
        'fecha_hora',
        'num_uso',
        'resultado',
        'fotografias',
        'observaciones',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'fotografias' => 'json',
    ];

    // Resultados posibles
    public const RESULTADOS = [
        'permitido' => 'Permitido',
        'denegado' => 'Denegado',
        'expirado' => 'Expirado',
    ];

    /**
     * Relación con el código QR
     */
    public function codigoQr()
    {
        return $this->belongsTo(QrCodigo::class, 'codigo_qr_id');
    }

    /**
     * Relación con el dispositivo
     */
    public function dispositivo()
    {
        return $this->belongsTo(QrDispositivo::class, 'dispositivo_id');
    }

    /**
     * Relación con el invitado (a través del código QR)
     */
    public function invitado()
    {
        return $this->hasOneThrough(
            QrInvitado::class,
            QrCodigo::class,
            'id', // Foreign key on QrCodigo table
            'id', // Foreign key on QrInvitado table
            'codigo_qr_id', // Local key on QrAcceso table
            'invitado_id' // Local key on QrCodigo table
        );
    }

    /**
     * Scope para accesos permitidos
     */
    public function scopePermitidos($query)
    {
        return $query->where('resultado', 'permitido');
    }

    /**
     * Scope para accesos denegados
     */
    public function scopeDenegados($query)
    {
        return $query->where('resultado', 'denegado');
    }

    /**
     * Scope para accesos por dispositivo
     */
    public function scopePorDispositivo($query, $dispositivoId)
    {
        return $query->where('dispositivo_id', $dispositivoId);
    }

    /**
     * Scope para accesos en un rango de fechas
     */
    public function scopeEntreFechas($query, $desde, $hasta)
    {
        return $query->whereBetween('fecha_hora', [$desde, $hasta]);
    }

    /**
     * Verifica si el acceso fue permitido
     */
    public function fuePermitido(): bool
    {
        return $this->resultado === 'permitido';
    }
}
