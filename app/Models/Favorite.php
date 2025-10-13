<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'favoritable_id',
        'favoritable_type'
    ];

    // ====================================
    // RELACIONES
    // ====================================

    /**
     * Un favorito pertenece a un usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación polimórfica - puede ser producto o servicio
     */
    public function favoritable()
    {
        return $this->morphTo();
    }

    // ====================================
    // SCOPES
    // ====================================

    /**
     * Scope para favoritos de productos
     */
    public function scopeProducts($query)
    {
        return $query->where('favoritable_type', Product::class);
    }

    /**
     * Scope para favoritos de servicios
     */
    public function scopeServices($query)
    {
        return $query->where('favoritable_type', Servicio::class);
    }

    /**
     * Scope para favoritos de un usuario específico
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
