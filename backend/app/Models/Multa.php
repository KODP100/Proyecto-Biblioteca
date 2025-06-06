<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Multa extends Model
{
    protected $table = 'multas';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'rango_dias',
        'monto',
    ];

}
