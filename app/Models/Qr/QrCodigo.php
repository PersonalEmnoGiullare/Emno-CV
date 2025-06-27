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
        // validamos que la fecha no haya expirado 
        if ($this->fecha_expiracion && now()->gte($this->fecha_expiracion)) {
            $this->estado = 'expirado';
            $this->save();
            return false;
        }

        // validamos que el codigo no haya sido usado completamente
        if ($this->usos_restantes <= 0) {
            $this->estado = 'usado';
            $this->save();
            return false;
        }

        return $this->estado === 'activo';
    }

    // funcion para decrementar usos restantes
    public function decrementUsosRestantes()
    {
        if ($this->usos_restantes > 0) {
            $this->usos_restantes--;
            if ($this->usos_restantes <= 0) {
                $this->estado = 'usado';
            }
            $this->ultima_fecha_uso = now();
            $this->save();
        }
    }
}
