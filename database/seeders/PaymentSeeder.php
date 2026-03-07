<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('payments')->insert([
            [
                'order_id' => 1,
                'payment_method' => 'Transfer Bank',
                'payment_amount' => 150000.00,
                'payment_proof' => 'bukti_transfer_1.jpg',
                'status' => 'Diterima',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => 2,
                'payment_method' => 'E-Wallet',
                'payment_amount' => 200000.00,
                'payment_proof' => 'bukti_ewallet_1.jpg',
                'status' => 'Diproses',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => 3,
                'payment_method' => 'Kartu Kredit',
                'payment_amount' => 175000.00,
                'payment_proof' => 'bukti_kredit_1.jpg',
                'status' => 'Ditolak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}