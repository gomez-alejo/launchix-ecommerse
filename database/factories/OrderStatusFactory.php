<?php

// database/factories/OrderStatusFactory.php
namespace Database\Factories;

use App\Models\OrderStatus;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderStatusFactory extends Factory
{
    protected $model = OrderStatus::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'status' => fake()->randomElement(['pending', 'processing', 'shipped', 'delivered', 'cancelled']),
            'comment' => fake()->boolean(50) ? fake()->sentence() : null,
            'changed_at' => fake()->dateTimeBetween('-3 months', 'now'),
        ];
    }
}
