<?php

// database/factories/OrderFactory.php
namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 20000, 500000);
        $tax = $subtotal * 0.19;
        $shippingCost = fake()->randomFloat(2, 5000, 15000);
        $total = $subtotal + $tax + $shippingCost;
        
        return [
            'user_id' => User::factory(),
            'user_address_id' => UserAddress::factory(),
            'order_number' => 'ORD-' . fake()->unique()->numerify('########'),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping_cost' => $shippingCost,
            'total' => $total,
            'status' => fake()->randomElement(['pending', 'processing', 'shipped', 'delivered', 'cancelled']),
            'notes' => fake()->boolean(40) ? fake()->sentence() : null,
            'ordered_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    public function delivered(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'delivered',
        ]);
    }
}
