<?php

// database/factories/PaymentMethodFactory.php
namespace Database\Factories;

use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentMethodFactory extends Factory
{
    protected $model = PaymentMethod::class;

    public function definition(): array
    {
        $methods = [
            'Tarjeta de Crédito' => 'Pago con tarjeta de crédito',
            'Tarjeta de Débito' => 'Pago con tarjeta de débito',
            'PSE' => 'Pago electrónico desde cuenta bancaria',
            'Efectivo' => 'Pago en efectivo contra entrega',
            'Nequi' => 'Pago mediante Nequi',
            'Daviplata' => 'Pago mediante Daviplata',
        ];

        $name = fake()->unique()->randomElement(array_keys($methods));

        return [
            'name' => $name,
            'description' => $methods[$name],
            'active' => fake()->boolean(90),
        ];
    }
}
