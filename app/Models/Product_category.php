<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_category extends Model
{
    use HasFactory;

    protected $table = 'product_categories';

    protected $fillable = [
        'product_id',
        'category_id'
    ];

    // ====================================
    // RELACIONES
    // ====================================

    /**
     * Tabla pivote - pertenece a un producto
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Tabla pivote - pertenece a una categoría
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
