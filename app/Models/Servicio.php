<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'servicios';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nombre_servicio',
        'categoria',
        'descripcion',
        'direccion',
        'telefono',
        'precio_base',
        'horario_atencion',
        'imagen_principal',
        'galeria_imagenes',
        'user_id',
        'status'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'galeria_imagenes' => 'array',
        'precio_base' => 'decimal:2'
    ];

    /**
     * Relación con el emprendedor que creó el servicio
     */
    public function entrepreneur()
    {
        return $this->belongsTo(Entrepreneur::class, 'user_id');
    }

    /**
     * Relationship with User (if you use authentication)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Un servicio puede estar en muchos favoritos (morph many)
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    /**
     * Un servicio puede tener muchas reseñas
     */
    public function reviews()
    {
        return $this->hasMany(reviews::class, 'servicio_id');
    }

    // ====================================
    // SCOPES PARA API
    // ====================================

    /**
     * Scope para servicios activos
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope para filtrar por categoría
     */
    public function scopeByCategory($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    /**
     * Scope para filtrar por ciudad (desde dirección)
     */
    public function scopeByCity($query, $city)
    {
        return $query->where('direccion', 'like', "%{$city}%");
    }

    /**
     * Scope para filtrar por rango de precio
     */
    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('precio_base', [$min, $max]);
    }

    /**
     * Scope para buscar servicios
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('nombre_servicio', 'like', "%{$search}%")
              ->orWhere('descripcion', 'like', "%{$search}%")
              ->orWhere('direccion', 'like', "%{$search}%");
        });
    }

    /**
     * Scope para servicios recientes
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // ====================================
    // ACCESSORS
    // ====================================

    /**
     * Accessor para URL de imagen principal
     */
    public function getImagenPrincipalUrlAttribute()
    {
        if ($this->imagen_principal) {
            if ($this->imagen_principal->startsWith('http')) {
                return $this->imagen_principal;
            }
            return asset('storage/' . $this->imagen_principal);
        }
        return asset('images/default-service.png');
    }

    /**
     * Accessor para verificar si tiene precio
     */
    public function getHasPriceAttribute()
    {
        return $this->precio_base > 0;
    }
}
