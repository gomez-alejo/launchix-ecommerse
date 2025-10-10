<?php

// database/factories/ServiceCategoryFactory.php
namespace Database\Factories;

use App\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ServiceCategoryFactory extends Factory
{
    protected $model = ServiceCategory::class;

    public function definition(): array
    {
        $categories = [
            'Plomería' => 'Servicios de plomería e instalaciones',
            'Electricidad' => 'Servicios eléctricos',
            'Carpintería' => 'Trabajos en madera y carpintería',
            'Limpieza' => 'Servicios de limpieza',
            'Jardinería' => 'Cuidado de jardines',
            'Belleza' => 'Servicios de belleza y estética',
            'Reparaciones' => 'Reparación de equipos',
            'Catering' => 'Servicios de alimentación',
        ];

        $name = fake()->unique()->randomElement(array_keys($categories));

        return [
            'name' => $name,
            'description' => $categories[$name],
            'icon' => fake()->boolean(60) ? 'icons/' . Str::slug($name) . '.svg' : null,
        ];
    }
}