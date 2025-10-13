<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'sales',
        'entrepreneur_id',
        'user_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'sales' => 'integer'
    ];

    // ====================================
    // RELACIONES
    // ====================================

    /**
     * Un producto pertenece a un emprendedor
     */
    public function entrepreneur()
    {
        return $this->belongsTo(Entrepreneur::class);
    }

    /**
     * Un producto pertenece a un usuario (respaldo)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ====================================
    // SCOPES PARA API
    // ====================================

    /**
     * Scope para productos con stock disponible
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Scope para productos populares (por ventas)
     */
    public function scopePopular($query)
    {
        return $query->orderBy('sales', 'desc');
    }

    /**
     * Scope para productos recientes
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope para buscar productos por nombre o descripción
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('description', 'LIKE', "%{$search}%");
        });
    }

    // ====================================
    // ACCESORIOS Y MUTADORES
    // ====================================

    /**
     * Obtener el precio formateado
     */
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Verificar si el producto está disponible
     */
    public function getIsAvailableAttribute()
    {
        return $this->stock > 0;
    }

    /**
     * Obtener URL slug del producto
     */
    public function getSlugAttribute()
    {
        return Str::slug($this->name);
    }
}
