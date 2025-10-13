<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_image extends Model
{
    use HasFactory;

    protected $table = 'product_images';

    protected $fillable = [
        'image_url',
        'product_id',
        'alt_text',
        'is_primary',
        'display_order',
        'file_size',
        'mime_type'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'display_order' => 'integer',
        'file_size' => 'integer'
    ];

    // ====================================
    // RELACIONES
    // ====================================

    /**
     * Una imagen pertenece a un producto
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
