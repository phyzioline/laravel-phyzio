<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        return [
            'type' => 'appointment',
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'currency' => 'USD',
            'status' => 'paid',
            'method' => 'card',
            'reference' => $this->faker->uuid,
            'meta' => [],
        ];
    }
}
