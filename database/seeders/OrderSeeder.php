<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use App\Models\Service;
use App\Models\OrderDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {

            // Pastikan ada data user & service dulu
            $users = User::all();
            $services = Service::all();

            if ($users->isEmpty() || $services->isEmpty()) {
                $this->command->warn('⚠️  Harap seed User dan Service dulu sebelum menjalankan OrderSeeder.');
                return;
            }

            // Buat beberapa order
            for ($i = 0; $i < 10; $i++) {
                $user = $users->random();

                $order = Order::create([
                    'user_id' => $user->id,
                    'order_number' => 'ORD-' . strtoupper(uniqid()),
                    'status' => fake()->randomElement(['diterima', 'diproses', 'selesai', 'lunas', 'belum lunas']),
                    'pickup_date' => $pickup = now()->subDays(rand(0, 5)),
                    'estimated_date' => (clone $pickup)->addDays(rand(2, 6)),
                    'total_amount' => 0,
                    'quantity' => 0,
                ]);

                $totalAmount = 0;
                $totalQty = 0;

                // Setiap order punya 2–4 layanan
                $serviceCount = rand(2, 4);

                for ($j = 0; $j < $serviceCount; $j++) {
                    $service = $services->random();
                    $quantity = fake()->numberBetween(1, 5);
                    $price = $service->price ?? fake()->numberBetween(5000, 20000);
                    $amount = $quantity * $price;
                    $estimated = (clone $pickup)->addDays(rand(1, 5));

                    OrderDetail::create([
                        'order_id' => $order->id,
                        'service_id' => $service->id,
                        'quantity' => $quantity,
                        'price' => $price,
                        'amount' => $amount,
                        'estimated_date' => $estimated,
                        'payment_status' => fake()->randomElement(['paid', 'unpaid']),
                    ]);

                    $totalAmount += $amount;
                    $totalQty += $quantity;
                }

                // Update total & quantity di order utama
                $order->update([
                    'total_amount' => $totalAmount,
                    'quantity' => $totalQty,
                ]);
            }

            $this->command->info('✅ Berhasil membuat 10 pesanan beserta detailnya!');
        });
    }
}
