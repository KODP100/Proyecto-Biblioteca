<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distrito extends Model
{
    protected $table = 'distritos';
    protected $primaryKey = 'id';
    public $timestamps = true;

    // Habilitar asignación masiva
    protected $fillable = [
        'nombre_distrito',
        'id_departamento',
    ];

    // Relación con Departamento
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'id_departamento');
    }

    public function municipios()
    {
    return $this->hasMany(Municipio::class, 'id_distrito');
    }

    // Relación con Empleado
    public function empleados()
    {
        return $this->hasMany(Empleado::class, 'id_distrito');
    }

    // Relación con Solicitante
    public function solicitantes()
    {
        return $this->hasMany(Solicitante::class, 'id_distrito');
    }
}
