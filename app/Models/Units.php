<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Units extends Model
{
    use HasFactory;

    protected $table = 'units'; // Nombre de la tabla

    protected $fillable = [
        'name',          // Nombre de la unidad (Pieza, Kilogramo, etc.)
        'abbreviation',  // Abreviatura de la unidad (pz, kg, etc.)
    ];
}
