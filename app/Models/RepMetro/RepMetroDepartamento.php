<?php

namespace App\Models\RepMetro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepMetroDepartamento extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rep_metro_departamentos';
    protected $fillable = ['nombre', 'descripcion'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function empleados()
    {
        return $this->hasMany(RepMetroEmpleado::class, 'id_departamento');
    }
}
