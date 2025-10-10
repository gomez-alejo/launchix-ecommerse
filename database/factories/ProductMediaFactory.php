<?php

// database/factories/ProductMediaFactory.php
namespace Database\Factories;

use App\Models\ProductMedia;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductMediaFactory extends Factory
{
    protected $model = ProductMedia::class;

    public function definition(): array
    {
        $type = fake()->randomElement(['image', 'image', 'image', 'video']); // 75% images
        
        return [
            'product_id' => Product::factory(),
            'url' => $type === 'image' 
                ? 'products/' . fake()->uuid() . '.jpg'
                : 'products/' . fake()->uuid() . '.mp4',
            'type' => $type,
            'order' => fake()->numberBetween(0, 10),
        ];
    }
}
