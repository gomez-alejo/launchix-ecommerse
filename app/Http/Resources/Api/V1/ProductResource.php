<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category,
            'description' => $this->description,
            'price' => $this->price,
            'formatted_price' => '$' . number_format($this->price, 0, ',', '.'),
            'stock' => $this->stock,
            'status' => $this->status,
            'featured' => (bool) $this->featured,
            'discount_percentage' => $this->discount_percentage,
            'discounted_price' => $this->discount_percentage ?
                $this->price * (1 - $this->discount_percentage / 100) : null,
            'views' => $this->views,
            'main_image' => $this->main_image,
            'gallery_images' => $this->gallery_images,
            'slug' => Str::slug($this->name),
            'is_available' => $this->stock > 0 && $this->status === 'active',
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),

            // Relaciones condicionales
            'entrepreneur' => $this->whenLoaded('entrepreneur', function() {
                return [
                    'id' => $this->entrepreneur->id,
                    'business_name' => $this->entrepreneur->business_name,
                    'business_type' => $this->entrepreneur->business_type,
                    'logo' => $this->entrepreneur->logo,
                    'verified' => (bool) $this->entrepreneur->verified
                ];
            }),

            'user' => $this->whenLoaded('user', function() {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'username' => $this->user->username
                ];
            }),

            'categories' => $this->whenLoaded('categories', function() {
                return $this->categories->map(function($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'description' => $category->description
                    ];
                });
            }),

            'images' => $this->whenLoaded('images', function() {
                return $this->images->map(function($image) {
                    return [
                        'id' => $image->id,
                        'url' => $image->url,
                        'alt_text' => $image->alt_text,
                        'is_main' => (bool) $image->is_main
                    ];
                });
            }),

            'reviews' => $this->whenLoaded('reviews', function() {
                return $this->reviews->map(function($review) {
                    return [
                        'id' => $review->id,
                        'rating' => $review->rating,
                        'comment' => $review->comment,
                        'user' => [
                            'name' => $review->user->name,
                            'username' => $review->user->username
                        ],
                        'created_at' => $review->created_at?->toISOString()
                    ];
                });
            }),

            // Estadísticas - solo cuando están disponibles
            'reviews_count' => $this->when(isset($this->reviews_count), $this->reviews_count),
            'favorites_count' => $this->when(isset($this->favorites_count), $this->favorites_count),
            'average_rating' => $this->when(isset($this->reviews_avg_rating),
                round($this->reviews_avg_rating, 1)),
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     */
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'resource_type' => 'product',
                'timestamp' => now()->toISOString()
            ]
        ];
    }
}
