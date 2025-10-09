<?php

// app/Models/Service.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'entrepreneur_id',
        'service_category_id',
        'name',
        'description',
        'price_from',
        'available',
        'business_hours',
        'average_rating',
        'published_at',
    ];

    protected $casts = [
        'price_from' => 'decimal:2',
        'available' => 'boolean',
        'average_rating' => 'decimal:2',
        'published_at' => 'datetime',
    ];

    public function entrepreneur()
    {
        return $this->belongsTo(Entrepreneur::class);
    }

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function media()
    {
        return $this->hasMany(ServiceMedia::class);
    }

    public function reviews()
    {
        return $this->hasMany(ServiceReview::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'service_favorites')
            ->withTimestamps()
            ->withPivot('added_at');
    }
}
