<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_Role extends Model
{
    use HasFactory;

    protected $table = 'user__roles';

    protected $fillable = [
        'user_id',
        'role_id'
    ];

    // ====================================
    // RELACIONES
    // ====================================

    /**
     * Tabla pivote - pertenece a un usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Tabla pivote - pertenece a un rol
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
