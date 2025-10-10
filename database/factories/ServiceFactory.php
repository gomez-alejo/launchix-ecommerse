<?php

// database/factories/ServiceFactory.php
namespace Database\Factories;

use App\Models\Service;
use App\Models\Entrepreneur;
use App\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        $published = fake()->boolean(85);
        
        return [
            'entrepreneur_id' => Entrepreneur::factory(),
            'service_category_id' => ServiceCategory::factory(),
            'name' => fake()->words(fake()->numberBetween(2, 5), true),
            'description' => fake()->paragraphs(3, true),
            'price_from' => fake()->randomFloat(2, 20000, 300000),
            'available' => fake()->boolean(90),
            'business_hours' => fake()->randomElement([
                'Lun-Vie: 8am-6pm',
                'Lun-Sab: 9am-5pm',
                '24/7',
                'Lun-Vie: 7am-7pm, Sab: 8am-2pm'
            ]),
            'average_rating' => fake()->randomFloat(2, 0, 5),
            'published_at' => $published ? fake()->dateTimeBetween('-1 year', 'now') : null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'available' => true,
        ]);
    }
}
