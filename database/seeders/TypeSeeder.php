<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('type')->insert([
           [
            'type_code' => 'ELEC',
            'type_name' => 'Electronics',
            'description' => 'Devices and gadgets such as phones, laptops, and TVs.'
           ],
              [
                'type_code' => 'FURN',
                'type_name' => 'Furniture',
                'description' => 'Household items such as tables, chairs, and sofas.'
              ],
              [
                'type_code' => 'CLOT',
                'type_name' => 'Clothing',
                'description' => 'Apparel items including shirts, pants, and dresses.'
              ]
        ]);
    }
}