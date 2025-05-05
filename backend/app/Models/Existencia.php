<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Existencia extends Model
{
    protected $table = 'existencias';
    protected $primaryKey = 'id_existencia';
    public $timestamps = true;

    // Relacion con Libro
    public function libro()
    {
        return $this->belongsTo(Libro::class, 'id_libro');
    }

    // Relacion con Prestamo
    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class, 'id_prestamo');
    }

    // Relacion con Devolucion
    public function devolucion()
    {
        return $this->belongsTo(Devolucion::class, 'id_devolucion');
    }
}
