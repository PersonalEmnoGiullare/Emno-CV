<?php

namespace App\Models\RepMetro;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepMetroEmpleado extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rep_metro_empleado';
    protected $fillable = ['id_usuario', 'id_departamento', 'id_puesto'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function departamento()
    {
        return $this->belongsTo(RepMetroDepartamento::class, 'id_departamento');
    }

    public function puesto()
    {
        return $this->belongsTo(RepMetroPuesto::class, 'id_puesto');
    }
}
