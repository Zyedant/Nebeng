<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartnerPostSeeder extends Seeder
{
    public function run()
    {
        DB::table('partner_posts')->insert([
            [
                'user_id' => 6, 
                'id_fullname' => 'Pos Mitra Terminal',
                'id_number' => '3201013004880001',
                'id_image' => 'partner_posts/id_pos_mitra.jpg',
                'terminal_name' => 'Terminal Kebayoran',
                'terminal_province_id' => 1, 
                'terminal_regency_id' => 3, 
                'terminal_district_id' => 4, 
                'terminal_map_coordinate' => '-6.243107,106.799851',
                'terminal_address' => 'Jl. Kebayoran Baru No. 123, Jakarta Selatan',
                'verified_status' => 'Terverifikasi',
                'verified_status_message' => 'Terminal telah diverifikasi dan aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}