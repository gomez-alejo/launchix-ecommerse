<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class reviews extends Model
{
    use HasFactory;

    protected $fillable = [
        'rating',
        'comment',
        'reviewed_at',
        'product_id',
        'user_id'
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'rating' => 'integer'
    ];

    // ====================================
    // RELACIONES
    // ====================================

    /**
     * Una reseña pertenece a un producto
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Una reseña pertenece a un usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
