<?php

// app/Models/EntrepreneurAddress.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntrepreneurAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'entrepreneur_id',
        'address',
        'city',
        'department',
        'postal_code',
        'latitude',
        'longitude',
        'is_main',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_main' => 'boolean',
    ];

    public function entrepreneur()
    {
        return $this->belongsTo(Entrepreneur::class);
    }
}
