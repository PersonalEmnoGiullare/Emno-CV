<?php

namespace App\Models\Qr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QrCodigo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'qr_codigos';

    protected $fillable = [
        'invitado_id',
        'codigo',
        'fecha_expiracion',
        'ultima_fecha_uso',
        'usos_restantes',
        'estado',
    ];

    protected $casts = [
        'fecha_generacion' => 'datetime',
        'fecha_expiracion' => 'datetime',
        'ultima_fecha_uso' => 'datetime',
    ];

    // Estados posibles
    public const ESTADOS = [
        'activo' => 'Activo',
        'usado' => 'Usado',
        'expirado' => 'Expirado',
        'cancelado' => 'Cancelado',
    ];

    // Relación con el invitado
    public function invitado()
    {
        return $this->belongsTo(QrInvitado::class, 'invitado_id');
    }

    // Relación con accesos
    public function accesos()
    {
        return $this->hasMany(QrAcceso::class, 'codigo_qr_id');
    }

    // Verificar si el código está activo
    public function estaActivo()
    {
        return $this->estado === 'activo' &&
            ($this->usos_restantes > 0) &&
            (!$this->fecha_expiracion || now()->lt($this->fecha_expiracion));
    }
}
