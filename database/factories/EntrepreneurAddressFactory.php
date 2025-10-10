<?php

// database/factories/EntrepreneurAddressFactory.php
namespace Database\Factories;

use App\Models\EntrepreneurAddress;
use App\Models\Entrepreneur;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntrepreneurAddressFactory extends Factory
{
    protected $model = EntrepreneurAddress::class;

    public function definition(): array
    {
        return [
            'entrepreneur_id' => Entrepreneur::factory(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'department' => fake()->randomElement([
                'Cundinamarca', 'Antioquia', 'Valle del Cauca', 'Atlántico', 
                'Santander', 'Bolívar', 'Boyacá', 'Tolima'
            ]),
            'postal_code' => fake()->numerify('#####'),
            'latitude' => fake()->latitude(1, 12),
            'longitude' => fake()->longitude(-79, -66),
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
