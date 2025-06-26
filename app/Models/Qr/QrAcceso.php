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
        'fecha_hora',
        'num_uso',
        'dispositivo',
        'direccion_ip',
        'ubicacion',
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

    // Relación con el código QR
    public function codigoQr()
    {
        return $this->belongsTo(QrCodigo::class, 'codigo_qr_id');
    }

    // Relación con el invitado (a través del código QR)
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
}
