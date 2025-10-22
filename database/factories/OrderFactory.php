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
        $createdAt = collect([
            now(),
            now()->addDay(),
            now()->addDays(12),
            now()->subMonth(),
            now()->subMonths(rand(2, 10))
        ])->random(1)->first();

        return [
            'user_id' => User::query()->inRandomOrder()->first()->id,
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'status' => fake()->randomElement(['diterima', 'diproses', 'selesai', 'lunas', 'belum lunas']),
            'pickup_date' => $createdAt,
            // estimasi global order (nanti tergantung dari detail)
            'estimated_date' => (clone $createdAt)->addDays(rand(2, 4)),
            'total_amount' => fake()->numberBetween(50000, 200000),
            'quantity' => fake()->numberBetween(1, 10),
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}
