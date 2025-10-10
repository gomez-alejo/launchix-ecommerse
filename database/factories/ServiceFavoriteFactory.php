<?php

// database/factories/ServiceFavoriteFactory.php
namespace Database\Factories;

use App\Models\ServiceFavorite;
use App\Models\User;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFavoriteFactory extends Factory
{
    protected $model = ServiceFavorite::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'service_id' => Service::factory(),
            'added_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
