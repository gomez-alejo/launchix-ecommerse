<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntrepreneurAddress extends Model
{
    use HasFactory;

    protected $table = 'entrepreneur_addresses';

    protected $fillable = [
        'entrepreneur_id',
        'address',
        'city',
        'department',
        'postal_code',
        'latitude',
        'longitude',
        'reference',
        'is_main',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_main' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * ========================================
     * RELACIONES
     * ========================================
     */

    public function entrepreneur()
    {
        return $this->belongsTo(Entrepreneur::class, 'entrepreneur_id');
    }

    /**
     * ========================================
     * ACCESSORS
     * ========================================
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

    public function getMainLabelAttribute()
    {
        return $this->is_main ? 'Principal' : 'Secundaria';
    }

    /**
     * ========================================
     * SCOPES
     * ========================================
     */

    public function scopeMain($query)
    {
        return $query->where('is_main', true);
    }

    public function scopeSecondary($query)
    {
        return $query->where('is_main', false);
    }

    public function scopeOrderedByMain($query)
    {
        return $query->orderBy('is_main', 'desc')->orderBy('created_at', 'desc');
    }

    /**
     * ========================================
     * MÉTODOS AUXILIARES
     * ========================================
     */

    public function isMain()
    {
        return $this->is_main === true;
    }

    public function makeMain()
    {
        // Desmarcar todas las direcciones del emprendedor
        self::where('entrepreneur_id', $this->entrepreneur_id)
            ->where('id', '!=', $this->id)
            ->update(['is_main' => false]);

        // Marcar esta como principal
        $this->update(['is_main' => true]);

        return $this;
    }

    public function hasReference()
    {
        return !empty($this->reference);
    }

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

    protected static function boot()
    {
        parent::boot();

        // Antes de crear: Si es la primera dirección, marcarla como principal
        static::creating(function ($address) {
            if (!isset($address->is_main)) {
                $entrepreneurAddressCount = self::where('entrepreneur_id', $address->entrepreneur_id)->count();
                $address->is_main = ($entrepreneurAddressCount === 0);
            }
        });

        // Antes de actualizar: Si se marca como principal, desmarcar las demás
        static::updating(function ($address) {
            if ($address->is_main && $address->isDirty('is_main')) {
                self::where('entrepreneur_id', $address->entrepreneur_id)
                    ->where('id', '!=', $address->id)
                    ->update(['is_main' => false]);
            }
        });

        // Antes de eliminar: Si es la principal, marcar otra como principal
        static::deleting(function ($address) {
            if ($address->is_main) {
                $nextAddress = self::where('entrepreneur_id', $address->entrepreneur_id)
                    ->where('id', '!=', $address->id)
                    ->first();

                if ($nextAddress) {
                    $nextAddress->update(['is_main' => true]);
                }
            }
        });
    }
}