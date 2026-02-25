<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class equipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('equipments')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $equipmentTypes = [
            'Máy chạy bộ' => 1, 'Xe đạp tập' => 1, 'Máy chèo thuyền' => 1,
            'Máy đẩy ngực' => 2, 'Máy kéo xô' => 2, 'Giá gánh tạ' => 2,
            'Ghế tập bụng' => 3, 'Tạ tay' => 3, 'Tạ ấm' => 3,
        ];

        $manufacturers = ['Technogym', 'Life Fitness', 'Matrix', 'Precor', 'Impulse'];

        for ($i = 1; $i <= 50; $i++) {
            $name = array_rand($equipmentTypes);
            $typeId = $equipmentTypes[$name];
            DB::table('equipments')->insert([
                'ten_thiet_bi' => $name . ' ' . $manufacturers[array_rand($manufacturers)] . ' S' . rand(100, 999),
                'type_id' => $typeId,
                'supplier_id' => rand(1, 3), 
                'room_id' => rand(1, 4), 
                'tinh_trang' => rand(1, 3), // 1: Tốt, 2: Bảo trì, 3: Hỏng
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
