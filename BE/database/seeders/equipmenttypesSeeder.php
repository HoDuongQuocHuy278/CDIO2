<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class equipmenttypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('equipment_types')->truncate();
        DB::table('equipment_types')->insert([
            [
                'ten_loai' => 'Cardio',
            ],
            [
                'ten_loai' => 'Máy tập sức mạnh',
            ],
            [
                'ten_loai' => 'Phụ kiện',
            ]

        ]);
    }
}
