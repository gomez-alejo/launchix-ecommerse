<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Genera 10 productos de ejemplo
        Product::factory()->count(10)->create();
    }
}