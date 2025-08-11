<?php


namespace Database\Factories;


use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'rating' => $this->faker->randomFloat(1, 0, 5),
        ];
    }

    public function run(): void
{
    Product::factory()->count(10)->create();
}
}