<?php

// database/factories/UserAddressFactory.php
namespace Database\Factories;

use App\Models\UserAddress;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserAddressFactory extends Factory
{
    protected $model = UserAddress::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'department' => fake()->randomElement([
                'Cundinamarca', 'Antioquia', 'Valle del Cauca', 'Atlántico', 
                'Santander', 'Bolívar', 'Boyacá', 'Tolima'
            ]),
            'postal_code' => fake()->numerify('#####'),
            'reference' => fake()->boolean(60) ? fake()->sentence() : null,
            'is_main' => false,
        ];
    }

    public function main(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_main' => true,
        ]);
    }
}

