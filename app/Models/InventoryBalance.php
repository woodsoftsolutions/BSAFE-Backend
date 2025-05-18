<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'unit_cost',
        'date',
        'warehouse_id',
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
}