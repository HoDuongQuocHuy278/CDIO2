<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->truncate();
        DB::table('products')->insert([
            [
                'ten_san_pham' => 'Whey Protein 2kg',
                'gia_ban'      => 1200000,
                'so_luong'     => 45,
                'danh_muc'     => 'Thực phẩm bổ sung',
                
            ],
            [
                'ten_san_pham' => 'BCAA 300g',
                'gia_ban'      => 450000,
                'so_luong'     => 12,
                'danh_muc'     => 'Thực phẩm bổ sung',
                
            ],
            [
                'ten_san_pham' => 'Găng tay tập gym',
                'gia_ban'      => 150000,
                'so_luong'     => 78,
                'danh_muc'     => 'Phụ kiện',

            ],
        ]);
    }
}
