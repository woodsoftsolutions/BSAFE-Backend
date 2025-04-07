<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouses extends Model
{
    use HasFactory;

    protected $table = 'warehouses'; // Nombre de la tabla

    protected $fillable = [
        'name',        // Nombre del almacén
        'location',    // Ubicación del almacén (puede ser nulo)
        'is_active',   // Estado del almacén (activo/inactivo)
    ];
}
