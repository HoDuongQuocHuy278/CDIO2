<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\PhanQuyen;
use App\Models\NguoiDung;
use App\Models\Employee;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $adminRole = PhanQuyen::create(['ten_chuc_vu' => 'Admin']);
        $employeeRole = PhanQuyen::create(['ten_chuc_vu' => 'Nhân viên']);
        $trainerRole = PhanQuyen::create(['ten_chuc_vu' => 'Huấn luyện viên']);

        // Create admin user
        $adminUser = NguoiDung::create([
            'ten_dang_nhap' => 'admin',
            'mat_khau' => Hash::make('admin123'),
            'role_id' => $adminRole->id,
        ]);

        // Create admin employee profile
        Employee::create([
            'ho_ten' => 'Administrator',
            'ngay_sinh' => '1990-01-01',
            'sdt' => '0123456789',
            'dia_chi' => 'Hà Nội',
            'email' => 'admin@gym.com',
            'user_id' => $adminUser->id,
        ]);

        // Create sample employee user
        $empUser = NguoiDung::create([
            'ten_dang_nhap' => 'nhanvien1',
            'mat_khau' => Hash::make('123456'),
            'role_id' => $employeeRole->id,
        ]);

        Employee::create([
            'ho_ten' => 'Nguyễn Văn A',
            'ngay_sinh' => '1995-05-15',
            'sdt' => '0987654321',
            'dia_chi' => 'TP.HCM',
            'email' => 'nhanvien1@gym.com',
            'user_id' => $empUser->id,
        ]);
    }
}
