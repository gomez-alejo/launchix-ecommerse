<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_addresses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'address',
        'city',
        'department',
        'postal_code',
        'reference',
        'is_main',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_main' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * ========================================
     * RELACIONES
     * ========================================
     */

    /**
     * Usuario propietario de la dirección
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Pedidos asociados a esta dirección
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'id_direccion');
    }

    /**
     * ========================================
     * ACCESSORS
     * ========================================
     */

    /**
     * Obtener dirección completa formateada
     */
    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->department,
            $this->postal_code
        ]);

        return implode(', ', $parts);
    }

    /**
     * Obtener etiqueta de dirección principal
     */
    public function getMainLabelAttribute()
    {
        return $this->is_main ? 'Principal' : 'Secundaria';
    }

    /**
     * ========================================
     * SCOPES
     * ========================================
     */

    /**
     * Scope para obtener solo direcciones principales
     */
    public function scopeMain($query)
    {
        return $query->where('is_main', true);
    }

    /**
     * Scope para obtener solo direcciones secundarias
     */
    public function scopeSecondary($query)
    {
        return $query->where('is_main', false);
    }

    /**
     * Scope para ordenar por principal primero
     */
    public function scopeOrderedByMain($query)
    {
        return $query->orderBy('is_main', 'desc')->orderBy('created_at', 'desc');
    }

    /**
     * ========================================
     * MÉTODOS AUXILIARES
     * ========================================
     */

    /**
     * Verificar si es la dirección principal
     */
    public function isMain()
    {
        return $this->is_main === true;
    }

    /**
     * Marcar esta dirección como principal
     * (Desmarca automáticamente las otras del mismo usuario)
     */
    public function makeMain()
    {
        // Desmarcar todas las direcciones del usuario
        self::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_main' => false]);

        // Marcar esta como principal
        $this->update(['is_main' => true]);

        return $this;
    }

    /**
     * Verificar si la dirección tiene referencia
     */
    public function hasReference()
    {
        return !empty($this->reference);
    }

    /**
     * Verificar si la dirección está completa
     */
    public function isComplete()
    {
        return !empty($this->address) && 
               !empty($this->city) && 
               !empty($this->department);
    }

    /**
     * ========================================
     * EVENTOS DEL MODELO
     * ========================================
     */

    /**
     * Boot del modelo
     */
    protected static function boot()
    {
        parent::boot();

        // Antes de crear: Si es la primera dirección del usuario, marcarla como principal
        static::creating(function ($address) {
            if (!isset($address->is_main)) {
                $userAddressCount = self::where('user_id', $address->user_id)->count();
                $address->is_main = ($userAddressCount === 0);
            }
        });

        // Antes de actualizar: Si se marca como principal, desmarcar las demás
        static::updating(function ($address) {
            if ($address->is_main && $address->isDirty('is_main')) {
                self::where('user_id', $address->user_id)
                    ->where('id', '!=', $address->id)
                    ->update(['is_main' => false]);
            }
        });

        // Antes de eliminar: Si es la principal y hay más direcciones, marcar otra como principal
        static::deleting(function ($address) {
            if ($address->is_main) {
                $nextAddress = self::where('user_id', $address->user_id)
                    ->where('id', '!=', $address->id)
                    ->first();

                if ($nextAddress) {
                    $nextAddress->update(['is_main' => true]);
                }
            }
        });
    }
}