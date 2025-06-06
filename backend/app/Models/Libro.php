<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Libro extends Model
{
    use HasFactory;

    protected $table = 'libros';

    protected $fillable = [
        'titulo',
        'anio_publicacion',
        'num_paginas',
        'id_editorial',
        'id_categoria',
        'id_estado_libro',
        'id_autor',
        'existencias',
    ];

    // Relaciones

    public function editorial()
    {
        return $this->belongsTo(Editorial::class, 'id_editorial');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    public function estadoLibro()
    {
        return $this->belongsTo(EstadoLibro::class, 'id_estado_libro');
    }

    public function autor()
    {
        return $this->belongsTo(Autor::class, 'id_autor');
    }

    public function prestamos()
{
    return $this->hasMany(Prestamo::class, 'id_libro');
}


}
