<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    use HasFactory;

    // Nombre correcto de la tabla
    protected $table = 'autores';

    // Campos asignables masivamente
    protected $fillable = [
        'nombre',
        'apellido',
        'nacionalidad',
    ];

    // Relación con libros (si se usa en el futuro)
    public function libros()
    {
        return $this->belongsToMany(Libro::class);
    }
}
