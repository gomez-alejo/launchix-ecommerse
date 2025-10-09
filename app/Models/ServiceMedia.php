<?php

// app/Models/ServiceMedia.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'url',
        'type',
        'order',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}