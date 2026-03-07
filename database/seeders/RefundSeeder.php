<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefundSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('refunds')->insert([
            [
                'order_id' => 3,
                'reason' => 'Pesanan dibatalkan oleh customer',
                'status' => 'Diproses',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => 2,
                'reason' => 'Kelebihan pembayaran',
                'status' => 'Diterima',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => 1,
                'reason' => 'Pelayanan tidak sesuai',
                'status' => 'Ditolak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}