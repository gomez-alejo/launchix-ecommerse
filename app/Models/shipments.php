<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shipments extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'address_id',
        'shipped_at',
        'delivered_at',
        'company',
        'tracking_number'
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime'
    ];

    // ====================================
    // RELACIONES
    // ====================================

    /**
     * Un envío pertenece a una orden
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Un envío pertenece a una dirección
     */
    public function address()
    {
        return $this->belongsTo(Addresses::class);
    }
}
