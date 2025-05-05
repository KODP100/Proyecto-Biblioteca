<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $table = 'libros';
    protected $primaryKey = 'id_libro';
    public $timestamps = true;

    // Relacion con Categoria
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    // Relacion con Editorial
    public function editorial()
    {
        return $this->belongsTo(Editorial::class, 'id_editorial');
    }

    // Relacion con Autor
    public function autor()
    {
        return $this->belongsTo(Autor::class, 'id_autor');
    }

    // Relacion con Existencia
    public function existencias()
    {
        return $this->hasMany(Existencia::class, 'id_libro');
    }

    // Relacion con Prestamo
    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'id_libro');
    }
}
