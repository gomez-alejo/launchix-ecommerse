<?php

// database/factories/ProductReviewFactory.php
namespace Database\Factories;

use App\Models\ProductReview;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductReviewFactory extends Factory
{
    protected $model = ProductReview::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'user_id' => User::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->boolean(80) ? fake()->paragraph() : null,
            'reviewed_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
