<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('supplier')->insert([
            [
                'supplier_name' => 'Ega',
                'contact' => '+62',
                'supplier_email' => 'supp@gmail.com',
                'supplier_status' => 'active'
            ]
        ]);
    }
}