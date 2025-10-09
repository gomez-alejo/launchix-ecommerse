<?php

// app/Models/Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'entrepreneur_id',
        'product_category_id',
        'name',
        'description',
        'price',
        'stock',
        'sales',
        'available',
        'average_rating',
        'published_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'available' => 'boolean',
        'average_rating' => 'decimal:2',
        'published_at' => 'datetime',
    ];

    public function entrepreneur()
    {
        return $this->belongsTo(Entrepreneur::class);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function media()
    {
        return $this->hasMany(ProductMedia::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'product_favorites')
            ->withTimestamps()
            ->withPivot('added_at');
    }
}
