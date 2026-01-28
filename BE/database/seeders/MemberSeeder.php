<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MemberSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('members')->truncate();
        DB::table('members')->insert([
            [
                'full_name' => 'Nguyễn Văn An',
                'email' => 'an.nguyen@gmail.com',
                'phone' => '0901234567',
                'address' => 'Hà Nội',
                'avatar' => 'avatars/an.png',
                'status' => 1,
                'package_name' => 'Premium',
                'package_duration' => 6,
                'start_date' => '2026-01-15',
                'end_date' => '2026-07-15',
                'absent_days' => 1, // ngày vắng mặt
                'warning_level' => 0,
            ],
            [
                'full_name' => 'Trần Thị Bình',
                'email' => 'binh.tran@gmail.com',
                'phone' => '0912345678',
                'address' => 'TP HCM',
                'avatar' => 'avatars/binh.png',
                'status' => 1,
                'package_name' => 'Standard',
                'package_duration' => 1,
                'start_date' => '2026-04-01',
                'end_date' => '2026-05-01',
                'absent_days' => 8,
                'warning_level' => 1,

            ],
            [
                'full_name' => 'Lê Hoàng Cường',
                'email' => 'cuong.gym@hotmail.com',
                'phone' => '0988777666',
                'address' => 'Đà Nẵng',
                'avatar' => 'avatars/cuong.png',
                'status' => 1,
                'package_name' => 'VIP',
                'package_duration' => 12,
                'start_date' => '2025-01-20',
                'end_date' => '2026-01-20',
                'absent_days' => 18,
                'warning_level' => 2,
            ],
        ]);
    }
}
