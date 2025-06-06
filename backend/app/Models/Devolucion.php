<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devolucion extends Model
{
    use HasFactory;

    protected $table = 'devoluciones'; // 👈 ¡Aquí corriges el nombre de la tabla!

    protected $fillable = [
        'id_prestamo',
        'fecha_devolucion',
        'estado_libro',
        'observaciones',
    ];

    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class, 'id_prestamo');
    }
}
