<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories'; // Nombre de la tabla

    protected $fillable = [
        'name', // Campos permitidos para asignación masiva
    ];
}
