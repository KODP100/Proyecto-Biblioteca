<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = ['nombre', 'descripcion'];

    // Relacion con Libro
    public function libros()
    {
        return $this->hasMany(Libro::class, 'id_categoria');
    }
}
