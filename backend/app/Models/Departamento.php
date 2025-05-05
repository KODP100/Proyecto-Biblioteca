<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'departamentos';
    protected $fillable = ['nombre_depto'];
    public $timestamps = true;

    // Relaciones
    public function municipios()
    {
        return $this->hasMany(Municipio::class, 'id_departamento');
    }

    public function empleados()
    {
        return $this->hasMany(Empleado::class, 'id_departamento');
    }

    public function solicitantes()
    {
        return $this->hasMany(Solicitante::class, 'id_departamento');
    }
}
