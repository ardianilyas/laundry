<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use App\Models\Service;
use App\Models\OrderDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {

            $users = User::all();
            $services = Service::all();
        
            if ($users->isEmpty() || $services->isEmpty()) {
                $this->command->warn('⚠️  Harap seed User dan Service dulu sebelum menjalankan OrderSeeder.');
                return;
            }
        
            for ($i = 0; $i < 100; $i++) {
        
                $user = $users->random();
        
                $monthsBack = rand(0, 5);
        
                $pickup = now()
                    ->subMonths($monthsBack)
                    ->day(rand(1, now()->subMonths($monthsBack)->daysInMonth));

                $estimatedPickup = (clone $pickup)->addDays(rand(2, 6));
        
                $order = Order::create([
                    'user_id' => $user->id,
                    'order_number' => 'ORD-' . strtoupper(uniqid()),
                    'status' => fake()->randomElement(['diterima', 'diproses', 'selesai', 'lunas', 'belum lunas']),
                    'pickup_date' => $pickup,
                    'estimated_date' => $estimatedPickup,
                    'total_amount' => 0,
                    'quantity' => 0,
                ]);
        
                $totalAmount = 0;
                $totalQty = 0;
        
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
        
                $order->update([
                    'total_amount' => $totalAmount,
                    'quantity' => $totalQty,
                ]);
            }
        
            $this->command->info('✅ Berhasil membuat 100 pesanan beserta detailnya!');
        });        
    }
}
