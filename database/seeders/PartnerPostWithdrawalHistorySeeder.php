<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartnerPostWithdrawalHistorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('partner_post_withdrawal_histories')->insert([
            [
                'partner_posts_id' => 1, 
                'amount' => '1000000',
                'status' => 'Diproses',
                'transfer_proof' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'partner_posts_id' => 1,
                'amount' => '1500000',
                'status' => 'Diterima',
                'transfer_proof' => 'partner_posts/bukti_transfer_terminal.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'partner_posts_id' => 1,
                'amount' => '500000',
                'status' => 'Ditolak',
                'transfer_proof' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
