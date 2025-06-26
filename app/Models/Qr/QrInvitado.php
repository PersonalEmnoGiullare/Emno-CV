<?php

namespace App\Models\Qr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QrInvitado extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'qr_invitados';

    protected $fillable = [
        'residente_id',
        'nombre',
        'apellido_pat',
        'apellido_mat',
        'alias',
        'telefono',
        'motivo_visita',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Relación con el residente
    public function residente()
    {
        return $this->belongsTo(QrResidente::class, 'residente_id');
    }

    // Relación con vehículos
    public function vehiculos()
    {
        return $this->hasMany(QrVehiculo::class, 'invitado_id');
    }

    // Relación con códigos QR
    public function codigos()
    {
        return $this->hasMany(QrCodigo::class, 'invitado_id');
    }

    // Relación con accesos (a través de códigos)
    public function accesos()
    {
        return $this->hasManyThrough(
            QrAcceso::class,
            QrCodigo::class,
            'invitado_id', // Foreign key on QrCodigo table
            'codigo_qr_id', // Foreign key on QrAcceso table
            'id', // Local key on QrInvitado table
            'id' // Local key on QrCodigo table
        );
    }

    // Método para nombre completo
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellido_pat} {$this->apellido_mat}";
    }
}
