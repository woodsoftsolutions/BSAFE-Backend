<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'movement_type',
        'quantity',
        'unit_cost',
        'total_cost',
        'warehouse_id',
        'order_id',
        'delivery_note_id',
        'employee_id',
    ];

    /**
     * Relación con el modelo Product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relación con el modelo Warehouse.
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Relación con el modelo Order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relación con el modelo DeliveryNote.
     */
    public function deliveryNote()
    {
        return $this->belongsTo(DeliveryNote::class);
    }

    /**
     * Relación con el modelo Employee.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}