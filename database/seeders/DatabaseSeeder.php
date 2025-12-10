<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Service;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $admin = User::factory()->create([
            'name' => 'Ardian Ilyas',
            'email' => 'ardian@developer.com',
            'password' => bcrypt('developer')
        ]);

        Role::create(['name' => 'admin']);
        
        $admin->assignRole('admin');

        User::factory(8)->create();
        // Service::factory(2)->create();

        $this->call([
            ServiceSeeder::class,
            OrderSeeder::class,
        ]);
        // Order::factory(350)->has(OrderDetail::factory(1), 'orderDetail')->create();
    }
}
