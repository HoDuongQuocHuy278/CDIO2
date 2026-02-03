<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('services')->truncate();
        DB::table('services')->insert([
            [
                'ten_dich_vu' => 'Gói tập Gym',
                'mo_ta' => 'Các gói tập gym',
                'status' => 1,
            ],
            [
                'ten_dich_vu' => 'PT',
                'mo_ta' => 'Dịch vụ huấn luyện viên cá nhân',
                'status' => 1,
            ],
            [
                'ten_dich_vu' => 'Yoga',
                'mo_ta' => 'Các lớp yoga',
                'status' => 1,
            ],
            [
                'ten_dich_vu' => 'Spa',
                'mo_ta' => 'Dịch vụ chăm sóc & thư giãn',
                'status' => 1,
            ],
           
        ]);
    }
}
