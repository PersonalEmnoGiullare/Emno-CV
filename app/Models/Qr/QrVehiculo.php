<?php

namespace App\Models\Qr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QrVehiculo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'qr_vehiculos';

    protected $fillable = [
        'invitado_id',
        'marca',
        'modelo',
        'color',
        'placas',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Relación con el invitado
    public function invitado()
    {
        return $this->belongsTo(QrInvitado::class, 'invitado_id');
    }

    // Método para descripción completa del vehículo
    public function getDescripcionCompletaAttribute()
    {
        return "{$this->marca} {$this->modelo} {$this->color} - {$this->placas}";
    }
}
