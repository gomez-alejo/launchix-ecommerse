<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'phone',
        'birthdate',
        'main_address',
        'city',
        'postal_code',
        'department',
        'avatar',
        'phone_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthdate' => 'date',
        'registered_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];

    // ====================================
    // RELACIONES
    // ====================================

    /**
     * Un usuario puede tener un carrito
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Un usuario puede tener muchas órdenes
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Un usuario puede tener muchas direcciones
     */
    public function addresses()
    {
        return $this->hasMany(Addresses::class);
    }

    /**
     * Un usuario puede escribir muchas reseñas
     */
    public function reviews()
    {
        return $this->hasMany(reviews::class);
    }

    /**
     * Un usuario puede tener muchos roles (many-to-many)
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user__roles');
    }

    /**
     * Un usuario puede crear productos (si es emprendedor)
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Un usuario puede crear servicios
     */
    public function servicios()
    {
        return $this->hasMany(Servicio::class);
    }

    /**
     * Un usuario puede tener muchos favoritos
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Un usuario puede tener muchos pagos
     */
    public function payments()
    {
        return $this->hasMany(payments::class);
    }

    // ====================================
    // SCOPES
    // ====================================

    /**
     * Scope para filtrar usuarios activos
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Scope para filtrar por ciudad
     */
    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    /**
     * Scope para filtrar por departamento
     */
    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    /**
     * Scope para buscar por nombre o email
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('username', 'like', "%{$search}%");
        });
    }

    /**
     * Scope para usuarios verificados
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    // ====================================
    // ACCESSORS
    // ====================================

    /**
     * Accessor para obtener URL del avatar
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        return "https://ui-avatars.com/api/?name=" .
            urlencode($this->name) .
            "&size=200&background=3B82F6&color=fff&bold=true";
    }

    /**
     * Accessor para verificar si es emprendedor
     */
    public function getIsEntrepreneurAttribute()
    {
        return $this->roles()->where('name', 'entrepreneur')->exists();
    }
}
