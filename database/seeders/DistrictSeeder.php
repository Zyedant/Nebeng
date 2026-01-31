<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictSeeder extends Seeder
{
    public function run()
    {
        $districts = [
            ['regency_id' => 1, 'name' => 'Gambir', 'created_at' => now(), 'updated_at' => now()],
            ['regency_id' => 1, 'name' => 'Tanah Abang', 'created_at' => now(), 'updated_at' => now()],
            ['regency_id' => 1, 'name' => 'Menteng', 'created_at' => now(), 'updated_at' => now()],
            
            ['regency_id' => 3, 'name' => 'Kebayoran Baru', 'created_at' => now(), 'updated_at' => now()],
            ['regency_id' => 3, 'name' => 'Tebet', 'created_at' => now(), 'updated_at' => now()],
            ['regency_id' => 3, 'name' => 'Setiabudi', 'created_at' => now(), 'updated_at' => now()],
            
            ['regency_id' => 6, 'name' => 'Bandung Wetan', 'created_at' => now(), 'updated_at' => now()],
            ['regency_id' => 6, 'name' => 'Coblong', 'created_at' => now(), 'updated_at' => now()],
            
            ['regency_id' => 12, 'name' => 'Genteng', 'created_at' => now(), 'updated_at' => now()],
            ['regency_id' => 12, 'name' => 'Tegalsari', 'created_at' => now(), 'updated_at' => now()],
        ];
        
        DB::table('districts')->insert($districts);
    }
}