<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nombre',
        'apellido',
        'edad',
        'telefono',
        'id_departamento',
        'id_municipio',
        'alta_empleado',
        'baja_empleado',
        'direccion',
    ];

    // Relación con Departamento
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'id_departamento');
    }

    // Relación con Municipio
    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'id_municipio');
    }
}
