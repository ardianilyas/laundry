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
            'created_at' => $createdAt = collect([
                now(),
                now()->addDay(),
                now()->addDays(12),
                now()->subMonth(),
                now()->subMonths(rand(2, 4))
                ])->random(1)->first(),
            'pickup_date' => $createdAt,
            'estimated_date' => $createdAt->addDays(rand(2, 4)),
            'updated_at' => $createdAt
        ];
    }
}
