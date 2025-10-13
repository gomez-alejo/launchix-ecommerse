<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'description',
        'type' // 'product' or 'service'
    ];
    // ====================================
    // RELACIONES
    // ====================================

    /**
     * Una categoría puede tener muchos productos (relación directa)
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category');
    }

    /**
     * Una categoría puede tener muchos productos (many-to-many)
     */
    public function productsMany()
    {
        return $this->belongsToMany(Product::class, 'product_categories');
    }

    /**
     * Una categoría puede tener muchos servicios
     */
    public function services()
    {
        return $this->hasMany(Servicio::class, 'categoria');
    }

}
