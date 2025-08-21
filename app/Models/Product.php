<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
    'name', 'category', 'description', 'price', 'stock',
    'main_image', 'gallery_images', 'entrepreneur_id', 'user_id'
    ];

    protected $casts = [
        'gallery_images' => 'array'
    ];

    public function entrepreneur()
    {
        return $this->belongsTo(Entrepreneur::class);
    }
    public function category()
{
    return $this->belongsTo(Category::class);
}

public function reviews()
{
    return $this->hasMany(reviews::class);
}
}
