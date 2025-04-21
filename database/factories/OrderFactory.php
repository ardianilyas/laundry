<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::all()->random()->id,
            'order_number' => 'ORD-' . uniqid(),
            'quantity' => fake()->randomFloat(1, 5, 20),
            'status' => fake()->randomElement(['diterima', 'diproses', 'selesai', 'lunas', 'belum lunas']),
            'pickup_date' => now(),
            'estimated_date' => now()->addDays(3)
        ];
    }
}
