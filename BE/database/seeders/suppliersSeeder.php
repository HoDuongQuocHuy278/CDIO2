<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class suppliersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('suppliers')->truncate();
        DB::table('suppliers')->insert([
            [
                'ten_nha_cung_cap' => 'Technogym Vietnam',
                'so_dien_thoai' => '0901234567',
                'dia_chi' => 'Quận 1, TP.HCM',
                'email' => 'contact@technogym.vn',
                'website' => 'https://www.technogym.com/vn',
                'tinh_trang'=> 1
            ],
            [
                'ten_nha_cung_cap' => 'Life Fitness',
                'so_dien_thoai' => '0912345678',
                'dia_chi' => 'Quận 3, TP.HCM',
                'email' => 'sales@lifefitness.vn',
                'website' => 'https://www.lifefitness.com',
                'tinh_trang'=> 1
            ],
            [
                'ten_nha_cung_cap' => 'Impulse Fitness',
                'so_dien_thoai' => '0923456789',
                'dia_chi' => 'Hà Nội',
                'email' => 'info@impulsefitness.vn',
                'website' => 'https://www.impulsefitness.com',
                'tinh_trang'=> 1
            ],

        ]);
    }
}
