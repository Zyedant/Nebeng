<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartnerSeeder extends Seeder
{
    public function run()
    {
        DB::table('partners')->insert([
            [
                'user_id' => 4,
                'id_fullname' => 'Budi Santoso',
                'id_number' => '3201011208850001',
                'id_birth_date' => '1985-08-12',
                'id_image' => 'partners/id_budi_santoso.jpg',
                'dl_fullname' => 'Budi Santoso',
                'dl_number' => 'SIM12345678',
                'dl_birth_date' => '1985-08-12',
                'dl_image' => 'partners/dl_budi_santoso.jpg',
                'verified_status' => 'Terverifikasi',
                'verified_status_message' => 'Verifikasi mitra berhasil',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 5,
                'id_fullname' => 'Siti Aminah',
                'id_number' => '3201012511900002',
                'id_birth_date' => '1990-11-25',
                'id_image' => 'partners/id_siti_aminah.jpg',
                'dl_fullname' => 'Siti Aminah',
                'dl_number' => 'SIM87654321',
                'dl_birth_date' => '1990-11-25',
                'dl_image' => 'partners/dl_siti_aminah.jpg',
                'verified_status' => 'Ditolak',
                'verified_status_message' => 'Foto KTP tidak jelas, silakan upload ulang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}