<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Editorial extends Model
{
    protected $table = 'editoriales';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = ['nombre', 'direccion', 'telefono', 'correo'];

    // Relacion con Libro
    public function libros()
    {
        return $this->hasMany(Libro::class, 'editorial_id');
    }

    // Relacion con Autor
    public function autores()
    {
        return $this->hasMany(Autor::class, 'editorial_id');
    }
}
