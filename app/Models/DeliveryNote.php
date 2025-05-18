<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'note_number',
        'order_id',
        'delivery_date',
        'delivery_address',
        'receiver_name',
        'receiver_dni',
        'notes',
        'delivered_by',
    ];

    /**
     * Relación con el modelo Order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relación con el modelo Employee (quien entregó).
     */
    public function deliveredBy()
    {
        return $this->belongsTo(Employee::class, 'delivered_by');
    }
}