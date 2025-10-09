<?php

// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birthdate' => 'date',
        'active' => 'boolean',
        'registered_at' => 'datetime',
    ];

    // Relaciones
    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function productReviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function serviceReviews()
    {
        return $this->hasMany(ServiceReview::class);
    }

    public function entrepreneurReviews()
    {
        return $this->hasMany(EntrepreneurReview::class);
    }

    public function favoriteProducts()
    {
        return $this->belongsToMany(Product::class, 'product_favorites')
            ->withTimestamps()
            ->withPivot('added_at');
    }

    public function favoriteServices()
    {
        return $this->belongsToMany(Service::class, 'service_favorites')
            ->withTimestamps()
            ->withPivot('added_at');
    }
}