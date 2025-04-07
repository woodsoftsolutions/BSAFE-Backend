<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Suppliers extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'suppliers'; // Nombre de la tabla

    protected $fillable = [
        'name',           // Nombre del proveedor
        'contact_person', // Persona de contacto
        'phone',          // Teléfono
        'email',          // Correo electrónico
        'address',        // Dirección
        'tax_id',         // Identificación fiscal (RIF / CI)
        'notes',          // Notas adicionales
        'active',         // Estado del proveedor (activo/inactivo)
    ];
}
