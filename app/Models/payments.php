<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payments extends Model
{
    use HasFactory;

    protected $fillable = [
        'method',
        'status',
        'amount',
        'paid_at',
        'order_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime'
    ];

    // ====================================
    // RELACIONES
    // ====================================

    /**
     * Un pago pertenece a una orden
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Un pago pertenece a un usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
