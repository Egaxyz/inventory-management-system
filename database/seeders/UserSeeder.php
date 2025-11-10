<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user')->insert([
            [
                'name' => 'Ega',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'status' => 'active',
                'email' => 'admin@gmail.com'
            ],
            [
                'name' => 'Gunawan',
                'password' => Hash::make('staff123'),
                'role' => 'staff',
                'status' => 'active',
                'email' => 'staff@gmail.com'
            ],
            [
                'name' => 'Siti',
                'password' => Hash::make('admin123'),
                'role' => 'staff',
                'status' => 'inactive',
                'email' => 'siti@gmail.com'
            ]
            
        ]);
    }
}