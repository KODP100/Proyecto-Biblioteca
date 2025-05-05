<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Multa extends Model
{
    protected $table = 'multas';
    protected $primaryKey = 'id_multa';
    public $timestamps = true;

    // Relacion con Devolucion
    public function devolucion()
    {
        return $this->belongsTo(Devolucion::class, 'id_devolucion');
    }

    // Relacion con Prestamo
    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class, 'id_prestamo');
    }
}
