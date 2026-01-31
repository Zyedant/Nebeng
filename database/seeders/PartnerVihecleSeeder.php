<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartnervihecleSeeder extends Seeder
{
    public function run()
    {
        DB::table('partner_vihecles')->insert([
            [
                'partner_id' => 1,
                'vihecle_type' => 'Mobil',
                'vihecle_plate_number' => 'B 1234 ABC',
                'vihecle_brand' => 'Toyota',
                'vihecle_name' => 'Avanza',
                'vihecle_color' => 'Hitam',
                'registration_number' => 'REG123456',
                'registration_vihecle_identity_number' => 'VIN12345678901234',
                'registration_engine_number' => 'ENG1234567890',
                'registration_image' => 'partner_vihecles/registration_budi.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'partner_id' => 1,
                'vihecle_type' => 'Motor',
                'vihecle_plate_number' => 'B 5678 XYZ',
                'vihecle_brand' => 'Honda',
                'vihecle_name' => 'Vario',
                'vihecle_color' => 'Merah',
                'registration_number' => 'REG654321',
                'registration_vihecle_identity_number' => 'VIN98765432109876',
                'registration_engine_number' => 'ENG0987654321',
                'registration_image' => 'partner_vihecles/registration_budi_motor.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'partner_id' => 2,
                'vihecle_type' => 'Mobil',
                'vihecle_plate_number' => 'B 8765 DEF',
                'vihecle_brand' => 'Daihatsu',
                'vihecle_name' => 'Xenia',
                'vihecle_color' => 'Putih',
                'registration_number' => 'REG112233',
                'registration_vihecle_identity_number' => 'VIN11223344556677',
                'registration_engine_number' => 'ENG1122334455',
                'registration_image' => 'partner_vihecles/registration_siti.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}