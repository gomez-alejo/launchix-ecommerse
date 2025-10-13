<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    // ====================================
    // RELACIONES
    // ====================================

    /**
     * Un rol puede tener muchos usuarios (many-to-many)
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user__roles');
    }
}
