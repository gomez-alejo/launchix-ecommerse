<?php

// database/factories/EntrepreneurReviewFactory.php
namespace Database\Factories;

use App\Models\EntrepreneurReview;
use App\Models\Entrepreneur;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntrepreneurReviewFactory extends Factory
{
    protected $model = EntrepreneurReview::class;

    public function definition(): array
    {
        return [
            'entrepreneur_id' => Entrepreneur::factory(),
            'user_id' => User::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->boolean(80) ? fake()->paragraph() : null,
            'reviewed_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
