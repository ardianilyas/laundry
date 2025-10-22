<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderDetail>
 */
class OrderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $service = Service::query()->inRandomOrder()->first();
        $quantity = fake()->numberBetween(1, 10);
        $price = $service->price ?? fake()->numberBetween(5000, 20000);
        $amount = $price * $quantity;

        return [
            'order_id' => Order::query()->inRandomOrder()->first()->id ?? Order::factory(),
            'service_id' => $service->id,
            'quantity' => $quantity,
            'price' => $price,
            'amount' => $amount,
            'estimated_date' => now()->addDays(rand(1, 5)),
            'payment_status' => fake()->randomElement(['paid', 'unpaid']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
