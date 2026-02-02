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
        DB::table('employees')->truncate();
        DB::table('employees')->insert([
            [
                'ho_ten'        => 'Nguyễn Văn Hùng',
                'ngay_sinh'     => '1995-04-15',
                'sdt'           => '0905123456',
                'dia_chi'       => 'Hải Châu, Đà Nẵng',
                'email'         => 'hunghlv@gym.com',
                'chuc_vu'       => 'Huấn luyện viên',
                'luong'         => 12000000,
                'ngay_vao_lam'  => '2022-01-10',
                'trang_thai'    => 0,
                'user_id'       => 1,
            ],
            [
                'ho_ten'        => 'Trần Thị Mai',
                'ngay_sinh'     => '1998-09-20',
                'sdt'           => '0912345678',
                'dia_chi'       => 'Liên Chiểu, Đà Nẵng',
                'email'         => 'mailt@gym.com',
                'chuc_vu'       => 'Lễ tân',
                'luong'         => 7000000,
                'ngay_vao_lam'  => '2023-03-01',
                'trang_thai'    => 1,
                'user_id'       => 2,
            ],
            [
                'ho_ten'        => 'Lê Quốc Bảo',
                'ngay_sinh'     => '1990-12-05',
                'sdt'           => '0987654321',
                'dia_chi'       => 'Sơn Trà, Đà Nẵng',
                'email'         => 'baoql@gym.com',
                'chuc_vu'       => 'Quản lý',
                'luong'         => 18000000,
                'ngay_vao_lam'  => '2021-06-15',
                'trang_thai'    => 2,
                'user_id'       => 3,

            ],

        ]);
    }
}
