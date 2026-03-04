<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('employees')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $roles = ['Huấn luyện viên', 'Lễ tân', 'Quản lý', 'Bảo vệ', 'Tư vấn viên'];
        $names = [
            'Lê Minh', 'Phan Hùng', 'Trịnh Hà', 'Đinh Thắng', 'Lâm Đào',
            'Đoàn Thịnh', 'Thân Thắng', 'Lương Ngọc', 'Hà Tuấn', 'Tô Cẩm',
            'Bạch Tùng', 
        ];

        foreach ($names as $index => $name) {
            DB::table('employees')->insert([
                'ho_ten' => $name,
                'ngay_sinh' => date('Y-m-d', strtotime('-' . rand(20, 45) . ' years')),
                'dia_chi' => 'Địa chỉ số ' . ($index + 1),
                'sdt' => '09' . str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT),
                'email' => 'employee' . ($index + 1) . '@wellfit.com',
                'chuc_vu' => $roles[array_rand($roles)],
                'trang_thai' => 1,
                'ngay_vao_lam' => date('Y-m-d', strtotime('-' . rand(0, 365) . ' days')),
                'luong' => rand(7, 25) * 1000000,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
