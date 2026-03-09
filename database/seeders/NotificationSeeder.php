<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('notifications')->insert([
            [
                'user_id' => 1,
                'title' => 'Pembayaran Berhasil',
                'description' => 'Pembayaran untuk order #001 telah berhasil diverifikasi',
                'read' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'title' => 'Order Baru',
                'description' => 'Anda menerima order baru dari customer',
                'read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'title' => 'Pengembalian Dana',
                'description' => 'Permintaan refund Anda sedang diproses',
                'read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'title' => 'Pengiriman Dimulai',
                'description' => 'Pesanan Anda sedang dalam perjalanan',
                'read' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}