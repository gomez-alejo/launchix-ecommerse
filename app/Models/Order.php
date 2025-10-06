<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'subtotal',
        'shipping_cost',
        'tax',
        'discount',
        'total',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_zip_code',
        'shipping_country',
        'payment_method',
        'payment_status',
        'paid_at',
        'notes',
        'tracking_number',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Relación con el usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con los items del pedido
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Generar número de orden único
     */
    public static function generateOrderNumber(): string
    {
        $prefix = 'ORD';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -6));
        
        return "{$prefix}-{$date}-{$random}";
    }

    /**
     * Scope para filtrar por estado
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope para pedidos del usuario actual
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para buscar pedidos
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('order_number', 'like', "%{$search}%")
              ->orWhere('shipping_name', 'like', "%{$search}%")
              ->orWhere('tracking_number', 'like', "%{$search}%");
        });
    }

    /**
     * Verificar si el pedido se puede cancelar
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['Pending', 'Processing']);
    }

    /**
     * Obtener el badge de estado
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'Pending' => 'bg-orange text-white',
            'Processing' => 'bg-primary text-dark',
            'Shipped' => 'bg-info text-white',
            'Delivered' => 'bg-green text-white',
            'Cancelled' => 'bg-error text-white',
            default => 'bg-gray-100 text-medium',
        };
    }

    /**
     * Obtener el texto del estado en español
     */
    public function getStatusText(): string
    {
        return match($this->status) {
            'Pending' => 'Pendiente',
            'Processing' => 'Procesando',
            'Shipped' => 'Enviado',
            'Delivered' => 'Entregado',
            'Cancelled' => 'Cancelado',
            default => $this->status,
        };
    }

    /**
     * Obtener el texto del método de pago en español
     */
    public function getPaymentMethodText(): string
    {
        return match($this->payment_method) {
            'cash' => 'Efectivo',
            'card' => 'Tarjeta',
            'transfer' => 'Transferencia',
            'paypal' => 'PayPal',
            default => $this->payment_method,
        };
    }
}