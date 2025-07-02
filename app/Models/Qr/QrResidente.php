<?php

namespace App\Models\Qr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\User;

class QrResidente extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'qr_residentes';

    protected $fillable = [
        'privada_id',
        'vivienda_id',
        'usuario_id',
        'telefono',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];


    // ------------------------------- metodos relacionales 
    // Relación con la privada
    public function privada()
    {
        return $this->belongsTo(QrPrivada::class, 'privada_id');
    }

    // Relación con la vivienda
    public function vivienda()
    {
        return $this->belongsTo(QrVivienda::class, 'vivienda_id');
    }

    // Relación con el usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Relación con invitados
    public function invitados()
    {
        return $this->hasMany(QrInvitado::class, 'residente_id');
    }

    // Relación con invitados activos (útil para filtrar)
    public function invitadosActivos()
    {
        return $this->hasMany(QrInvitado::class, 'residente_id')->where('activo', true);
    }

    // Relación con códigos QR a través de invitados
    public function codigos()
    {
        return $this->hasManyThrough(
            QrCodigo::class,      // Modelo final
            QrInvitado::class,    // Modelo intermedio
            'residente_id',       // Foreign key en qr_invitados
            'invitado_id',        // Foreign key en qr_codigos
            'id',                 // Local key en qr_residentes
            'id'                  // Local key en qr_invitados
        );
    }

    // Relación con códigos QR activos
    public function codigosActivos()
    {
        return $this->hasManyThrough(
            QrCodigo::class,
            QrInvitado::class,
            'residente_id',
            'invitado_id',
            'id',
            'id'
        )->where('qr_codigos.estado', 'activo');
    }


    // ------------------------------- metodos para mostrar datos
    // obtener el nombre completo del residente
    public function getNombreCompletoAttribute()
    {
        return $this->usuario ? $this->usuario->getFullNameAttribute() : 'Desconocido';
    }
}
