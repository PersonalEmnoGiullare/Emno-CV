<?php

namespace App\Models\Qr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QrPrivada extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'qr_privadas';

    protected $fillable = [
        'nombre',
        'constructora',
        'direccion',
        'ciudad',
        'codigo_postal',
        'activa',
        'configuracion',
    ];

    protected $casts = [
        'activa' => 'boolean',
        'configuracion' => 'json',
    ];

    // Relación con viviendas
    public function viviendas()
    {
        return $this->hasMany(QrVivienda::class, 'privada_id');
    }

    // Relación con residentes
    public function residentes()
    {
        return $this->hasMany(QrResidente::class, 'privada_id');
    }
}
