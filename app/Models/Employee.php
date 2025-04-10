<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'employees'; // Nombre de la tabla

    protected $fillable = [
        'user_id',              // Relación con usuario del sistema
        'first_name',           // Nombre del empleado
        'last_name',            // Apellido del empleado
        'dni',                  // Documento de identidad
        'phone',                // Teléfono
        'email',                // Correo electrónico
        'position',             // Cargo
        'hire_date',            // Fecha de contratación
        'can_manage_inventory', // Permiso para gestionar inventario
        'active',               // Estado del empleado (activo/inactivo)
    ];

    /**
     * Relación con el modelo User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
