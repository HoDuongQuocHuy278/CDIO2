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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('products')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = ['Thực phẩm bổ sung', 'Phụ kiện', 'Đồ uống', 'Trang phục'];
        $productPrefixes = [
            'Whey Protein', 'BCAA', 'Pre-workout', 'Creatine', 'Vitamin Tổng Hợp',
            'Găng tay', 'Đai lưng', 'Bình nước', 'Áo gym', 'Quần short',
            'Thảm tập', 'Dây kháng lực', 'Ốp cổ tay', 'Túi tập gym', 'Dầu cá Omega-3',
            'ZMA', 'Casein', 'Massage Gun', 'Foam Roller', 'Shaker'
        ];

        for ($i = 1; $i <= 50; $i++) {
            $prefix = $productPrefixes[array_rand($productPrefixes)];
            DB::table('products')->insert([
                'ten_san_pham' => $prefix . ' ' . (rand(1, 4) == 4 ? 'Gold Edition' : 'V' . rand(1, 5)),
                'gia_ban' => rand(5, 150) * 10,
                'so_luong' => rand(5, 200),
                'danh_muc' => $categories[array_rand($categories)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
