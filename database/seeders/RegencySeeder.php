<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegencySeeder extends Seeder
{
    public function run()
    {
        $regencies = [
            ['province_id' => 1, 'name' => 'Jakarta Pusat', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => 1, 'name' => 'Jakarta Utara', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => 1, 'name' => 'Jakarta Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => 1, 'name' => 'Jakarta Timur', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => 1, 'name' => 'Jakarta Barat', 'created_at' => now(), 'updated_at' => now()],
            
            ['province_id' => 2, 'name' => 'Bandung', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => 2, 'name' => 'Bekasi', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => 2, 'name' => 'Depok', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => 2, 'name' => 'Bogor', 'created_at' => now(), 'updated_at' => now()],
            
            ['province_id' => 3, 'name' => 'Semarang', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => 3, 'name' => 'Solo', 'created_at' => now(), 'updated_at' => now()],
            
            ['province_id' => 4, 'name' => 'Surabaya', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => 4, 'name' => 'Malang', 'created_at' => now(), 'updated_at' => now()],
            
            ['province_id' => 5, 'name' => 'Tangerang', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => 5, 'name' => 'Serang', 'created_at' => now(), 'updated_at' => now()],
            
            ['province_id' => 6, 'name' => 'Yogyakarta', 'created_at' => now(), 'updated_at' => now()],
            
            ['province_id' => 7, 'name' => 'Denpasar', 'created_at' => now(), 'updated_at' => now()],
        ];
        
        DB::table('regencies')->insert($regencies);
    }
}