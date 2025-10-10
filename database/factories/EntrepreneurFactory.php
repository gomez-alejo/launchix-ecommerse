<?php

// database/factories/EntrepreneurFactory.php
namespace Database\Factories;

use App\Models\Entrepreneur;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EntrepreneurFactory extends Factory
{
    protected $model = Entrepreneur::class;

    public function definition(): array
    {
        return [
            'business_name' => fake()->company(),
            'email' => fake()->unique()->companyEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => fake()->numerify('3#########'),
            'description' => fake()->paragraph(3),
            'logo' => fake()->boolean(40) ? 'logos/' . fake()->uuid() . '.jpg' : null,
            'average_rating' => fake()->randomFloat(2, 3.0, 5.0),
            'verified' => fake()->boolean(70),
            'active' => fake()->boolean(95),
            'registered_at' => fake()->dateTimeBetween('-2 years', 'now'),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'verified' => false,
            'email_verified_at' => null,
        ]);
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'verified' => true,
        ]);
    }
}
