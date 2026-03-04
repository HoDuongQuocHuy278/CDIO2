<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MemberSeeder extends Seeder
{

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('members')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $names = [
            'Nguyễn Văn An', 'Trần Thị Bình', 'Lê Hoàng Cường', 'Phạm Minh Đức', 'Vũ Thị Hoa',
            'Đặng Văn Hùng', 'Bùi Thị Lan', 'Ngô Văn Nam', 'Đỗ Thị Phượng', 'Hoàng Văn Quý',
            
        ];

        $packages = [
            ['name' => 'Gói tập Gym 1 tháng', 'price' => 500000, 'duration' => 1, 'service_id' => 1, 'service_name' => 'Gói tập Gym'],
            ['name' => 'PT cá nhân 12 buổi', 'price' => 3000000, 'duration' => 1, 'service_id' => 2, 'service_name' => 'PT'],
            ['name' => 'Yoga cơ bản', 'price' => 800000, 'duration' => 1, 'service_id' => 3, 'service_name' => 'Yoga'],
            ['name' => 'Premium', 'price' => 2000000, 'duration' => 6, 'service_id' => 1, 'service_name' => 'Gói tập Gym'],
            ['name' => 'VIP 12 Tháng', 'price' => 3500000, 'duration' => 12, 'service_id' => 1, 'service_name' => 'Gói tập Gym'],
        ];

        foreach ($names as $index => $name) {
            $package = $packages[array_rand($packages)];
            $startDate = date('Y-m-d', strtotime('-' . rand(0, 30) . ' days'));
            $endDate = date('Y-m-d', strtotime('+' . $package['duration'] . ' months', strtotime($startDate)));

            DB::table('members')->insert([
                'full_name' => $name,
                'email' => 'user' . ($index + 1) . '@example.com',
                'phone' => '09' . str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT),
                'address' => 'Thành phố ' . ($index + 1),
                'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=random',
                'status' => 1,
                'service_id' => $package['service_id'],
                'service_name' => $package['service_name'],
                'package_name' => $package['name'],
                'package_price' => $package['price'],
                'package_duration' => $package['duration'],
                'start_date' => $startDate,
                'end_date' => $endDate,
                'absent_days' => rand(0, 20),
                'warning_level' => rand(0, 2),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
