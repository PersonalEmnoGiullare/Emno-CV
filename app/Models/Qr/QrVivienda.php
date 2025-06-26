<?php

namespace App\Models\Qr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QrVivienda extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'qr_viviendas';

    protected $fillable = [
        'privada_id',
        'numero',
        'tipo',
        'calle',
        'seccion',
        'disponible',
        'observaciones',
    ];

    protected $casts = [
        'disponible' => 'boolean',
    ];

    // Relación con la privada
    public function privada()
    {
        return $this->belongsTo(QrPrivada::class, 'privada_id');
    }

    // Relación con residentes
    public function residentes()
    {
        return $this->hasMany(QrResidente::class, 'vivienda_id');
    }

    // Relación con el usuario principal (a través de residentes)
    public function usuarioPrincipal()
    {
        return $this->hasOne(QrResidente::class, 'vivienda_id')
            ->whereNotNull('usuario_id');
    }
}
