<?php

// database/factories/ServiceReviewFactory.php
namespace Database\Factories;

use App\Models\ServiceReview;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceReviewFactory extends Factory
{
    protected $model = ServiceReview::class;

    public function definition(): array
    {
        return [
            'service_id' => Service::factory(),
            'user_id' => User::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->boolean(80) ? fake()->paragraph() : null,
            'reviewed_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
