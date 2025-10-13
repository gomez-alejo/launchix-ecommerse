<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone,
            'city' => $this->city,
            'department' => $this->department,
            'birthdate' => $this->birthdate?->format('Y-m-d'),
            'main_address' => $this->main_address,
            'postal_code' => $this->postal_code,
            'profile_image' => $this->profile_image,
            'is_verified' => (bool) $this->is_verified,
            'status' => $this->status,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),

            // Conditional relationships - only when loaded (simple arrays for now)
            'roles' => $this->whenLoaded('roles', function() {
                return $this->roles->map(function($role) {
                    return [
                        'id' => $role->id,
                        'name' => $role->name,
                        'display_name' => $role->display_name
                    ];
                });
            }),
            'entrepreneur' => $this->whenLoaded('entrepreneur', function() {
                return $this->entrepreneur ? [
                    'id' => $this->entrepreneur->id,
                    'business_name' => $this->entrepreneur->business_name,
                    'business_type' => $this->entrepreneur->business_type,
                    'status' => $this->entrepreneur->status
                ] : null;
            }),

            // Counts - only when loaded
            'products_count' => $this->when(isset($this->products_count), $this->products_count),
            'services_count' => $this->when(isset($this->services_count), $this->services_count),
            'orders_count' => $this->when(isset($this->orders_count), $this->orders_count),
            'reviews_count' => $this->when(isset($this->reviews_count), $this->reviews_count)
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     */
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'resource_type' => 'user',
                'timestamp' => now()->toISOString()
            ]
        ];
    }
}
