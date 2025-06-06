<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    protected $fillable = [
        'id_libro',
        'numero_ejemplares',
        'fecha_prestamo',
        'fecha_devolucion_esperada',
        'id_multa',
        'monto_multa',
    ];

    public function libro()
    {
        return $this->belongsTo(Libro::class, 'id_libro');
    }


    public function multa()
    {
        return $this->belongsTo(Multa::class, 'id_multa');
    }
}
