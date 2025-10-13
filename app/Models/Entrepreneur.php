<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Entrepreneur extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'city',
        'address',
        'profile_description',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'registered_at' => 'datetime',
    ];

    // Accessor para obtener la URL completa del avatar
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo) {
            return Storage::url($this->profile_photo);
        }

        // Avatar por defecto con iniciales usando UI Avatars
        return "https://ui-avatars.com/api/?name=" .
            urlencode($this->first_name . ' ' . $this->last_name) .
            "&size=200&background=FDC040&color=fff&bold=true";
    }

    // Accessor para obtener nombre completo
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // ====================================
    // RELACIONES
    // ====================================

    /**
     * Un emprendedor puede tener muchos productos
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Un emprendedor puede tener muchos servicios
     */
    public function servicios()
    {
        return $this->hasMany(Servicio::class, 'user_id');
    }
}
