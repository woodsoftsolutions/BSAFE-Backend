<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customers'; // Nombre de la tabla

    protected $fillable = [
        'name',           // Nombre del cliente
        'contact_person', // Persona de contacto
        'phone',          // Teléfono
        'email',          // Correo electrónico
        'address',        // Dirección
        'tax_id',         // Identificación fiscal
        'customer_type',  // Tipo de cliente (regular, wholesaler, government)
        'notes',          // Notas adicionales
        'active'         // Estado del cliente (activo/inactivo)
    ];
}
