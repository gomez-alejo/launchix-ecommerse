<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'category', 'price', 'image_url', 'description', 'stock', 'entrepreneur_id', 'user_id', 'sales'
        // Agrega aquí los campos que realmente usas en tu migración
    ];

    public function entrepreneur()
    {
        return $this->belongsTo(Entrepreneur::class);
    }
}
