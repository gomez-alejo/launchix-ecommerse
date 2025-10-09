<?php

// app/Models/Entrepreneur.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Entrepreneur extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'business_name',
        'email',
        'password',
        'phone',
        'description',
        'logo',
        'average_rating',
        'verified',
        'active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'average_rating' => 'decimal:2',
        'verified' => 'boolean',
        'active' => 'boolean',
        'registered_at' => 'datetime',
    ];

    // Relaciones
    public function addresses()
    {
        return $this->hasMany(EntrepreneurAddress::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function reviews()
    {
        return $this->hasMany(EntrepreneurReview::class);
    }
}