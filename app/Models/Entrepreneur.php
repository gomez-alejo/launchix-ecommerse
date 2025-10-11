<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Entrepreneur extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'business_name',
        'email',
        'password',
        'phone',
        'description',
        'logo',
        'average_rating',
        'verified',
        'active',
        'registered_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'registered_at' => 'datetime',
            'password' => 'hashed',
            'verified' => 'boolean',
            'active' => 'boolean',
            'average_rating' => 'decimal:2',
        ];
    }

    /**
     * ========================================
     * RELACIONES
     * ========================================
     */

    public function products()
    {
        return $this->hasMany(Product::class, 'entrepreneur_id');
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'entrepreneur_id');
    }

    /**
     * CORREGIDO: Usar los nombres correctos de la migración
     */
    public function addresses()
    {
        return $this->hasMany(EntrepreneurAddress::class, 'entrepreneur_id');
    }

    /**
     * CORREGIDO: Usar is_main en lugar de principal
     */
    public function mainAddress()
    {
        return $this->hasOne(EntrepreneurAddress::class, 'entrepreneur_id')
                    ->where('is_main', true);
    }

    public function reviews()
    {
        return $this->hasMany(EntrepreneurReview::class, 'entrepreneur_id');
    }

    /**
     * ========================================
     * SCOPES
     * ========================================
     */

    public function scopeVerified($query)
    {
        return $query->where('verified', true);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * ========================================
     * ACCESSORS
     * ========================================
     */

    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : asset('images/default-logo.png');
    }

    public function getFormattedRegisteredDateAttribute()
    {
        return $this->registered_at ? $this->registered_at->format('d/m/Y') : '-';
    }

    /**
     * ========================================
     * MÉTODOS AUXILIARES
     * ========================================
     */

    public function updateAverageRating()
    {
        $avg = $this->reviews()->avg('rating');
        $this->update(['average_rating' => $avg ?? 0]);
        return $this;
    }

    public function isActiveAndVerified(): bool
    {
        return $this->active && $this->verified;
    }

    public function hasLogo(): bool
    {
        return !empty($this->logo);
    }

    public function hasMainAddress(): bool
    {
        return $this->mainAddress()->exists();
    }
}