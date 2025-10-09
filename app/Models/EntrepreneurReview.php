<?php

// app/Models/EntrepreneurReview.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntrepreneurReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'entrepreneur_id',
        'user_id',
        'rating',
        'comment',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function entrepreneur()
    {
        return $this->belongsTo(Entrepreneur::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
