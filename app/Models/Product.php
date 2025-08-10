<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'stock', 'entrepreneur_id'
    ];

    public function entrepreneur()
    {
        return $this->belongsTo(Entrepreneur::class);
    }
}
