<?php

// database/factories/ServiceMediaFactory.php
namespace Database\Factories;

use App\Models\ServiceMedia;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceMediaFactory extends Factory
{
    protected $model = ServiceMedia::class;

    public function definition(): array
    {
        $type = fake()->randomElement(['image', 'image', 'image', 'video']);
        
        return [
            'service_id' => Service::factory(),
            'url' => $type === 'image' 
                ? 'services/' . fake()->uuid() . '.jpg'
                : 'services/' . fake()->uuid() . '.mp4',
            'type' => $type,
            'order' => fake()->numberBetween(0, 10),
        ];
    }
}
