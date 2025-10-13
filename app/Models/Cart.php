<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id'
    ];

    // ====================================
    // RELACIONES
    // ====================================

    /**
     * Un carrito pertenece a un usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Un carrito puede tener muchos items
     */
    public function items()
    {
        return $this->hasMany(Cart_Item::class);
    }

    /**
     * Accessor para obtener el total del carrito
     */
    public function getTotalAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });
    }
}
