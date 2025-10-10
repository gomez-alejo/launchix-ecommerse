<?php

// database/factories/ProductFavoriteFactory.php
namespace Database\Factories;

use App\Models\ProductFavorite;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFavoriteFactory extends Factory
{
    protected $model = ProductFavorite::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'added_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
