<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WithdrawalHistorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('withdrawal_histories')->insert([
            [
                'partner_id' => 1,
                'amount' => '500000',
                'status' => 'Diproses',
                'transfer_proof' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'partner_id' => 1,
                'amount' => '750000',
                'status' => 'Diterima',
                'transfer_proof' => 'withdrawals/bukti_transfer_1.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'partner_id' => 2,
                'amount' => '300000',
                'status' => 'Ditolak',
                'transfer_proof' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
