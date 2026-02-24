<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartnerVehicleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('partner_vehicles')->insert([
            [
                'partner_id' => 1,
                'vehicle_type' => 'Mobil',
                'plate_number' => 'B 1234 ABC',
                'vehicle_brand' => 'Toyota',
                'vehicle_name' => 'Avanza',
                'color' => 'Hitam',
                'registration_number' => 'REG123456',
                'registration_vehicle_identity_number' => 'VIN12345678901234',
                'registration_engine_number' => 'ENG1234567890',
                'registration_image' => 'partner_vehicles/registration_budi.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'partner_id' => 1,
                'vehicle_type' => 'Motor',
                'plate_number' => 'B 5678 XYZ',
                'vehicle_brand' => 'Honda',
                'vehicle_name' => 'Vario',
                'color' => 'Merah',
                'registration_number' => 'REG654321',
                'registration_vehicle_identity_number' => 'VIN98765432109876',
                'registration_engine_number' => 'ENG0987654321',
                'registration_image' => 'partner_vehicles/registration_budi_motor.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'partner_id' => 2,
                'vehicle_type' => 'Mobil',
                'plate_number' => 'B 8765 DEF',
                'vehicle_brand' => 'Daihatsu',
                'vehicle_name' => 'Xenia',
                'color' => 'Putih',
                'registration_number' => 'REG112233',
                'registration_vehicle_identity_number' => 'VIN11223344556677',
                'registration_engine_number' => 'ENG1122334455',
                'registration_image' => 'partner_vehicles/registration_siti.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
