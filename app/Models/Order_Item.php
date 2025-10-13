<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_Item extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    protected $fillable = [
        'quantity',
        'unit_price',
        'order_id',
        'product_id'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2'
    ];

    // ====================================
    // RELACIONES
    // ====================================

    /**
     * Un item de orden pertenece a una orden
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Un item de orden pertenece a un producto
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Accessor para obtener el subtotal del item
     */
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->unit_price;
    }
}
