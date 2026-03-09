<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
    
        DB::table('orders')->insert([
            [
                'customer_id' => 1,
                'partner_id' => 1,
                'departure_post_id' => 1,
                'destination_post_id' => 1,
                'date' => '2024-01-15',
                'time' => '10:30:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_id' => 2,
                'partner_id' => 2,
                'departure_post_id' => 1,
                'destination_post_id' => 1,
                'date' => '2024-01-16',
                'time' => '14:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_id' => 3,
                'partner_id' => 1,
                'departure_post_id' => 1, 
                'destination_post_id' => 1, 
                'date' => '2024-01-17',
                'time' => '09:15:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}