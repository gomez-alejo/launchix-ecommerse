<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart_Item extends Model
{
    use HasFactory;

    protected $table = 'cart__items';

    protected $fillable = [
        'quantity',
        'unit_price',
        'cart_id',
        'product_id'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2'
    ];

    // ====================================
    // RELACIONES
    // ====================================

    /**
     * Un item del carrito pertenece a un carrito
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Un item del carrito pertenece a un producto
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
