<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitante extends Model
{
    protected $table = 'solicitantes';
    protected $primaryKey = 'id_solicitante';
    public $timestamps = true;

    // Relacion con Departamento
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'id_departamento');
    }

    // Relacion con Municipio
    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'id_municipio');
    }

    // Relacion con Distrito
    public function distrito()
    {
        return $this->belongsTo(Distrito::class, 'id_distrito');
    }
}
