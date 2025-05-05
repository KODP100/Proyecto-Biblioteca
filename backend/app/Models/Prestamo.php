<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    protected $table = 'prestamos';
    protected $primaryKey = 'id_prestamo';
    public $timestamps = true;

    // Relacion con Libro
    public function libro()
    {
        return $this->belongsTo(Libro::class, 'id_libro');
    }


    // Relacion con Devolucion
    public function devolucion()
    {
        return $this->hasOne(Devolucion::class, 'id_prestamo');
    }

    // Relacion con Multa
    public function multa()
    {
        return $this->hasOne(Multa::class, 'id_prestamo');
    }
}
