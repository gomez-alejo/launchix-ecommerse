<?php

// database/factories/ProductCategoryFactory.php
namespace Database\Factories;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductCategoryFactory extends Factory
{
    protected $model = ProductCategory::class;

    public function definition(): array
    {
        $categories = [
            'Alimentos y Bebidas' => 'Productos alimenticios y bebidas',
            'Ropa y Accesorios' => 'Prendas de vestir y complementos',
            'Artesanías' => 'Productos artesanales hechos a mano',
            'Belleza y Cuidado' => 'Productos de belleza y cuidado personal',
            'Hogar y Decoración' => 'Artículos para el hogar',
            'Tecnología' => 'Productos tecnológicos',
            'Libros y Educación' => 'Material educativo y literario',
            'Deportes' => 'Artículos deportivos',
        ];

        $name = fake()->unique()->randomElement(array_keys($categories));

        return [
            'name' => $name,
            'description' => $categories[$name],
            'icon' => fake()->boolean(60) ? 'icons/' . Str::slug($name) . '.svg' : null,
        ];
    }
}