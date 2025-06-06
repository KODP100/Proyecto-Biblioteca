<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Solicitante extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'apellido', 'edad', 'telefono', 'correo',
        'id_departamento', 'id_municipio', 'id_distrito',
        'user_id', 'alta_solicitante', 'baja_solicitante', 'direccion'
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'id_departamento');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'id_municipio');
    }

    public function distrito()
    {
        return $this->belongsTo(Distrito::class, 'id_distrito');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
