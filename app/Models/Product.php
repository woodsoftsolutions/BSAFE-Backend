<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'category_id',
        'unit_id',
        'current_stock',
        'minimum_stock',
        'active',
        'brand',
        'model',
    ];

    /**
     * Relación con el modelo Category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relación con el modelo Unit.
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}