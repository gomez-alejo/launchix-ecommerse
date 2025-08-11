<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'servicios';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nombre_servicio',
        'categoria',
        'descripcion',
        'direccion',
        'telefono',
        'precio_base',
        'horario_atencion',
        'imagen_principal',
        'galeria_imagenes',
        'user_id'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'galeria_imagenes' => 'array',
        'precio_base' => 'decimal:2'
    ];

    /**
     * Relationship with User (if you use authentication)
     */
    /* public function user()
    {
        return $this->belongsTo(User::class);
    } */
}