<?php


// database/factories/PaymentFactory.php
namespace Database\Factories;

use App\Models\Payment;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        $status = fake()->randomElement(['pending', 'completed', 'failed', 'refunded']);
        
        return [
            'order_id' => Order::factory(),
            'payment_method_id' => PaymentMethod::factory(),
            'amount' => fake()->randomFloat(2, 20000, 500000),
            'status' => $status,
            'transaction_reference' => $status !== 'pending' ? fake()->uuid() : null,
            'paid_at' => $status === 'completed' ? fake()->dateTimeBetween('-3 months', 'now') : null,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'transaction_reference' => fake()->uuid(),
            'paid_at' => fake()->dateTimeBetween('-3 months', 'now'),
        ]);
    }
}
