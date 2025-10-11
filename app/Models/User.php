<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'username',
        'email',
        'password',
        'phone',
        'birthdate',
        'profile_photo',
        'active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
    'birthdate' => 'date',
    'created_at' => 'datetime',  // Agregar esto
    'updated_at' => 'datetime',  // Agregar esto
    'active' => 'boolean',
];

    /**
     * ========================================
     * ACCESSORS
     * ========================================
     */

    /**
     * Obtener nombre completo del usuario
     */
    public function getFullNameAttribute()
    {
        return "{$this->name} {$this->last_name}";
    }

    /**
     * Obtener la URL completa de la foto de perfil
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo) {
            return Storage::url($this->profile_photo);
        }
        
        // Avatar por defecto con iniciales usando UI Avatars
        return "https://ui-avatars.com/api/?name=" . 
            urlencode($this->name . ' ' . $this->last_name) . 
            "&size=200&background=EB0924&color=fff&bold=true";
    }

    /**
     * Obtener la dirección principal del usuario
     * (Accessor para facilitar acceso desde cualquier parte)
     */
    public function getMainAddressAttribute()
    {
        return $this->addresses()->where('is_main', true)->first();
    }

    /**
     * ========================================
     * RELACIONES
     * ========================================
     */

    /**
     * Direcciones del usuario
     */
    public function addresses()
    {
        return $this->hasMany(UserAddress::class, 'user_id');
    }

    /**
     * Carrito del usuario
     */
    public function cart()
    {
        return $this->hasOne(Cart::class, 'id_usuario');
    }

    /**
     * Pedidos del usuario
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'id_usuario');
    }

    /**
     * Reseñas de productos del usuario
     */
    public function productReviews()
    {
        return $this->hasMany(ProductReview::class, 'id_usuario');
    }

    /**
     * Reseñas de servicios del usuario
     */
    public function serviceReviews()
    {
        return $this->hasMany(ServiceReview::class, 'id_usuario');
    }

    /**
     * Reseñas de emprendedores del usuario
     */
    public function entrepreneurReviews()
    {
        return $this->hasMany(EntrepreneurReview::class, 'id_usuario');
    }

    /**
     * Productos favoritos del usuario
     */
    public function favoriteProducts()
    {
        return $this->hasMany(FavoriteProduct::class, 'id_usuario');
    }

    /**
     * Servicios favoritos del usuario
     */
    public function favoriteServices()
    {
        return $this->hasMany(FavoriteService::class, 'id_usuario');
    }

    /**
     * ========================================
     * MÉTODOS AUXILIARES
     * ========================================
     */

    /**
     * Verificar si el usuario tiene una dirección principal
     */
    public function hasMainAddress()
    {
        return $this->addresses()->where('is_main', true)->exists();
    }

    /**
     * Verificar si el usuario está activo
     */
    public function isActive()
    {
        return $this->active === true;
    }

    /**
     * Obtener el número total de pedidos del usuario
     */
    public function getTotalOrdersAttribute()
    {
        return $this->orders()->count();
    }

    /**
     * Obtener el número total de reseñas escritas
     */
    public function getTotalReviewsAttribute()
    {
        return $this->productReviews()->count() + 
               $this->serviceReviews()->count() + 
               $this->entrepreneurReviews()->count();
    }
}