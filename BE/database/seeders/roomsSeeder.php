<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class roomsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rooms')->truncate();
        DB::table('rooms')->insert([
            [
                'ten_phong' => 'Phòng Cardio A',
            ],
            [
                'ten_phong' => 'Phòng Cardio B',
            ],
            [
                'ten_phong' => 'Phòng tập tạ',
            ],
            [
                'ten_phong' => 'Phòng Yoga',

            ],

        ]);
    }
}
