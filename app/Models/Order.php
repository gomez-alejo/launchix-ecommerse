<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'ordered_at',
        'user_id',
        'status'
    ];

    protected $casts = [
        'ordered_at' => 'datetime'
    ];

    // ====================================
    // RELACIONES
    // ====================================

    /**
     * Una orden pertenece a un usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Una orden puede tener muchos items
     */
    public function items()
    {
        return $this->hasMany(Order_Item::class);
    }

    /**
     * Una orden puede tener muchos pagos
     */
    public function payments()
    {
        return $this->hasMany(payments::class);
    }

    /**
     * Una orden puede tener muchos envíos
     */
    public function shipments()
    {
        return $this->hasMany(shipments::class);
    }

    /**
     * Una orden puede tener una dirección de envío
     */
    public function address()
    {
        return $this->belongsTo(Addresses::class, 'address_id');
    }

    /**
     * Accessor para obtener el total de la orden
     */
    public function getTotalAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });
    }
}
