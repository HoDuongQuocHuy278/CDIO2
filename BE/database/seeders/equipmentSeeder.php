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
        DB::table('equipments')->truncate();
        DB::table('equipments')->insert([
            [
                'ten_thiet_bi' => 'Máy chạy bộ Technogym',
                'type_id' => 1, // Cardio
                'supplier_id' => 1, // nhà cung cấp
                'room_id' => 1,
                'tinh_trang' => 1, // Tốt
            ],
            [
                'ten_thiet_bi' => 'Xe đạp tập',
                'type_id' => 1, // Cardio
                'supplier_id' => 1,
                'room_id' => 1,
                'tinh_trang' => 2, // Cần bảo trì
            ],
            [
                'ten_thiet_bi' => 'Máy kéo xô',
                'type_id' => 2, // Máy tập sức mạnh
                'supplier_id' => 2,
                'room_id' => 2,
                'tinh_trang' => 3, // Hỏng
            ]

        ]);
    }
}
