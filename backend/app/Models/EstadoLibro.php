<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoLibro extends Model
{
    protected $table = 'estado_libros';
    protected $primaryKey = 'id';
    public $timestamps = true;

    // ✅ Añade esto:
    protected $fillable = ['nombre', 'descripcion'];

    // Relación con Libro
    public function libros()
    {
        return $this->hasMany(Libro::class, 'id_estado_libro');
    }
}
