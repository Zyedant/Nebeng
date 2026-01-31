<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        DB::table('customers')->insert([
            [
                'user_id' => 7,
                'id_fullname' => 'John Doe',
                'id_number' => '3201011807950001',
                'id_image' => 'customers/id_john_doe.jpg',
                'verified_status' => 'Terverifikasi',
                'verified_status_message' => 'Verifikasi berhasil, data sesuai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 8,
                'id_fullname' => 'Jane Smith',
                'id_number' => '3201012202980002',
                'id_image' => 'customers/id_jane_smith.jpg',
                'verified_status' => 'Pengajuan',
                'verified_status_message' => 'Sedang dalam proses verifikasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 9,
                'id_fullname' => 'Ahmad Rizki',
                'id_number' => '3201011009930003',
                'id_image' => 'customers/id_ahmad_rizki.jpg',
                'verified_status' => 'Belum Verifikasi',
                'verified_status_message' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}