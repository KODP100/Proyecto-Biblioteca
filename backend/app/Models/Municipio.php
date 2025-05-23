<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    use HasFactory;

    protected $fillable = ['nombre_municipio', 'id_distrito'];

    public function distrito()
    {
        return $this->belongsTo(Distrito::class, 'id_distrito');
    }
}