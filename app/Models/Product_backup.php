<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    /**
     * Un producto pertenece a una categoría
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Un producto puede tener muchas reseñas
     */
    public function reviews()
    {
        return $this->hasMany(reviews::class);
    }

    /**
     * Un producto puede tener muchas imágenes
     */
    public function images()
    {
        return $this->hasMany(Product_image::class);
    }

    /**
     * Un producto puede estar en muchos carritos (many-to-many through cart_items)
     */
    public function cartItems()
    {
        return $this->hasMany(Cart_Item::class);
    }

    /**
     * Un producto puede estar en muchas órdenes (many-to-many through order_items)
     */
    public function orderItems()
    {
        return $this->hasMany(Order_Item::class);
    }

    /**
     * Un producto pertenece a muchas categorías (many-to-many)
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    /**
     * Un producto puede estar en muchos favoritos (morph many)
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
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
     * Scope para productos en oferta
     */
    public function scopeOnSale($query)
    {
        return $query->where('discount_percentage', '>', 0);
    }

    /**
     * Scope para productos mejor calificados
     */
    public function scopeTopRated($query, $minRating = 4.0)
    {
        return $query->whereHas('reviews', function($q) use ($minRating) {
            $q->havingRaw('AVG(rating) >= ?', [$minRating]);
        });
    }

    /**
     * Scope para productos recientes
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope para filtrar por rango de precio
     */
    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    /**
     * Scope para buscar productos
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Scope para ordenar por popularidad (vistas)
     */
    public function scopePopular($query)
    {
        return $query->orderBy('views', 'desc');
    }

    // ====================================
    // ACCESSORS
    // ====================================

    /**
     * Accessor para precio con descuento
     */
    public function getDiscountedPriceAttribute()
    {
        if ($this->discount_percentage > 0) {
            return $this->price * (1 - ($this->discount_percentage / 100));
        }
        return $this->price;
    }

    /**
     * Accessor para URL de imagen principal
     */
    public function getMainImageUrlAttribute()
    {
        if ($this->main_image) {
            return asset('storage/' . $this->main_image);
        }
        return asset('images/default-product.png');
    }

    /**
     * Accessor para verificar si tiene descuento
     */
    public function getHasDiscountAttribute()
    {
        return $this->discount_percentage > 0;
    }

    /**
     * Accessor para promedio de calificaciones
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
}
