<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devolucion extends Model
{
    protected $table = 'devoluciones';
    protected $primaryKey = 'id_devolucion';
    public $timestamps = true;

    // Relacion con Prestamo
    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class, 'id_prestamo');
    }

    // Relacion con Multa
    public function multa()
    {
        return $this->hasOne(Multa::class, 'id_devolucion');
    }
}
