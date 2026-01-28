<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admins')->truncate();
        DB::table('admins')->insert([
            [
                'id'            => 1,
                'ho_ten'        => 'Trần Xuân Trường',
                'email'         => 'truong081205@gmail.com',
                'password'      => bcrypt('123456'),
                'so_dien_thoai' => '0813559551',
                'hinh_anh'      => 'https://hoanghamobile.com/tin-tuc/wp-content/uploads/2024/07/anh-nu-cute-55.jpg',
                'id_chuc_vu'    => 1,// tổng giám đốc 
                'tinh_trang'    => 1,
            ],
            [
                'id'            => 2,
                'ho_ten'        => 'Võ Thị Thái Ngọc',
                'email'         => 'thaingoc081205@gmail.com',
                'password'      => bcrypt('123456'),
                'so_dien_thoai' => '0813559551',
                'hinh_anh'      => 'https://i.pravatar.cc/150?img=2',
                'id_chuc_vu'    => 2, // giảm đốc
                'tinh_trang'    => 1,
            ],
             [
                'id'            => 3,
                'ho_ten'        => 'Hồ Dương Quốc Huy',
                'email'         => 'quochuy081205@gmail.com',
                'password'      => bcrypt('123456'),
                'so_dien_thoai' => '0813559551',
                'hinh_anh'      => 'https://i.pravatar.cc/150?img=3',
                'id_chuc_vu'    => 3, // quản lý nhân sự
                'tinh_trang'    => 1,
            ],
             [
                'id'            => 4,
                'ho_ten'        => 'Nguyễn Nam Hung',
                'email'         => 'namhung081205@gmail.com',
                'password'      => bcrypt('123456'),
                'so_dien_thoai' => '0813559551',
                'hinh_anh'      => 'https://i.pravatar.cc/150?img=3',
                'id_chuc_vu'    => 4, // quản lý nhân sự
                'tinh_trang'    => 1,
            ],

                
        ]);
    }
}
