<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::query()->create([
            'name' => 'Cuci Setrika',
            'price' => 5000
        ]);

        Service::query()->create([
            'name' => 'Setrika',
            'price' => 3000
        ]);
    }
}
