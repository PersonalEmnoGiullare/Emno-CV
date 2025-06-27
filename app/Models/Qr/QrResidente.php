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

    // obtener el nombre completo del residente
    public function getNombreCompletoAttribute()
    {
        return $this->usuario ? $this->usuario->getFullNameAttribute() : 'Desconocido';
    }
}
