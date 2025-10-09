<?php

// app/Models/ProductMedia.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'url',
        'type',
        'order',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
