<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'email' => 'superadmin@nebeng.com',
                'password' => Hash::make('password123'),
                'name' => 'Super Admin',
                'phone_number' => '081234567890',
                'gender' => 'Laki-laki',
                'birth_date' => '1990-01-01',
                'birth_place' => 'Jakarta',
                'role' => 'Superadmin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            [
                'email' => 'admin@nebeng.com',
                'password' => Hash::make('password123'),
                'name' => 'Admin Nebeng',
                'phone_number' => '081234567891',
                'gender' => 'Perempuan',
                'birth_date' => '1992-05-15',
                'birth_place' => 'Bandung',
                'role' => 'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            [
                'email' => 'finance@nebeng.com',
                'password' => Hash::make('password123'),
                'name' => 'Finance Department',
                'phone_number' => '081234567892',
                'gender' => 'Laki-laki',
                'birth_date' => '1991-03-20',
                'birth_place' => 'Surabaya',
                'role' => 'Finance',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            [
                'email' => 'budi.santoso@example.com',
                'password' => Hash::make('password123'),
                'name' => 'Budi Santoso',
                'phone_number' => '081311223344',
                'gender' => 'Laki-laki',
                'birth_date' => '1985-08-12',
                'birth_place' => 'Jakarta',
                'role' => 'Mitra',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'siti.aminah@example.com',
                'password' => Hash::make('password123'),
                'name' => 'Siti Aminah',
                'phone_number' => '081322334455',
                'gender' => 'Perempuan',
                'birth_date' => '1990-11-25',
                'birth_place' => 'Bandung',
                'role' => 'Mitra',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            [
                'email' => 'pos.mitra@example.com',
                'password' => Hash::make('password123'),
                'name' => 'Pos Mitra Terminal',
                'phone_number' => '081333445566',
                'gender' => 'Laki-laki',
                'birth_date' => '1988-04-30',
                'birth_place' => 'Surabaya',
                'role' => 'Pos Mitra',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            [
                'email' => 'customer1@example.com',
                'password' => Hash::make('password123'),
                'name' => 'John Doe',
                'phone_number' => '081344556677',
                'gender' => 'Laki-laki',
                'birth_date' => '1995-07-18',
                'birth_place' => 'Yogyakarta',
                'role' => 'Customer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'customer2@example.com',
                'password' => Hash::make('password123'),
                'name' => 'Jane Smith',
                'phone_number' => '081355667788',
                'gender' => 'Perempuan',
                'birth_date' => '1998-02-22',
                'birth_place' => 'Bali',
                'role' => 'Customer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'customer3@example.com',
                'password' => Hash::make('password123'),
                'name' => 'Ahmad Rizki',
                'phone_number' => '081366778899',
                'gender' => 'Laki-laki',
                'birth_date' => '1993-09-10',
                'birth_place' => 'Jakarta',
                'role' => 'Customer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}