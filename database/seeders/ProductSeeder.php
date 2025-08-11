<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'name' => 'Camiseta Deportiva',
            'description' => 'Camiseta ligera y cómoda para entrenar.',
            'price' => 50000,
            'rating' => 4.5
        ]);

        Product::create([
            'name' => 'Zapatillas Running',
            'description' => 'Zapatillas para correr largas distancias.',
            'price' => 120000,
            'rating' => 4.8
        ]);

        Product::create([
            'name' => 'Botella de Agua',
            'description' => 'Botella reutilizable de 1 litro.',
            'price' => 20000,
            'rating' => 4.2
        ]);
    }
}