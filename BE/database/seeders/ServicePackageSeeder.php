<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicePackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('service_packages')->truncate();
        DB::table('service_packages')->insert([
           [
                'service_id' => 1,
                'ten_goi' => 'Gói tập Gym 1 tháng',
                'thoi_han_ngay' => 30,
                'so_buoi_tap' => null, // không giới hạn
                'gia_tien' => 500000,
                
           ],
           [
                'service_id' => 2,
                'ten_goi' => 'PT cá nhân 12 buổi',
                'thoi_han_ngay' => 30,
                'so_buoi_tap' => 12,
                'gia_tien' => 3000000,
                
            ],

            
            [
                'service_id' => 3,
                'ten_goi' => 'Yoga cơ bản',
                'thoi_han_ngay' => 30,
                'so_buoi_tap' => 8,
                'gia_tien' => 800000,
                
            ],

            
            [
                'service_id' => 4,
                'ten_goi' => 'Massage thư giãn 5 buổi',
                'thoi_han_ngay' => null,
                'so_buoi_tap' => 5,
                'gia_tien' => 600000,
                
            ],

        ]);
    }
}
