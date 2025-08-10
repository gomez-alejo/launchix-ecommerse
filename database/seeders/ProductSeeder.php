<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Camiseta Deportiva',
                'description' => 'Camiseta ligera y transpirable para entrenamiento.',
                'rating' => 4.5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Zapatillas Running',
                'description' => 'Zapatillas cómodas con amortiguación avanzada.',
                'rating' => 4.8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Botella de Agua',
                'description' => 'Botella reutilizable de 1 litro.',
                'rating' => 4.3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Guantes de Gimnasio',
                'description' => 'Guantes antideslizantes para entrenamiento de fuerza.',
                'rating' => 4.6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mochila Deportiva',
                'description' => 'Mochila resistente al agua con varios compartimentos.',
                'rating' => 4.7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}