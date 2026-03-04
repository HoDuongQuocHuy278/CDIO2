<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RevenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('revenues')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $sources = ['Gói tập', 'Dịch vụ', 'Sản phẩm'];
        $customers = ['Nguyễn Văn An', 'Trần Thị Bình', 'Lê Hoàng Cường', 'Phạm Minh Đức', 'Vũ Thị Hoa'];
        $items = [
            'Gói tập Gym' => 'Gói tập',
            'PT cá nhân' => 'Dịch vụ',
            'Yoga' => 'Dịch vụ',
            'Whey Protein' => 'Sản phẩm',
            'Bình nước' => 'Sản phẩm'
        ];

        for ($i = 1; $i <= 100; $i++) {
            $item = array_rand($items);
            $source = $items[$item];
            
            DB::table('revenues')->insert([
                'date' => date('Y-m-d', strtotime('-' . rand(0, 180) . ' days')),
                'customer' => $customers[array_rand($customers)],
                'source' => $source,
                'item' => $item,
                'amount' => rand(5, 100) * 10,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
