<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    public function run()
    {
        DB::table('provinces')->insert([
            ['name' => 'DKI Jakarta', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Jawa Barat', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Jawa Tengah', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Jawa Timur', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Banten', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'DI Yogyakarta', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bali', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}