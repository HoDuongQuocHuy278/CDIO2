<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('invoices')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $customers = [
            'Nguyễn Văn An', 'Trần Thị Bình', 'Lê Hoàng Cường', 'Phạm Minh Đức', 'Vũ Thị Hoa',
            'Đặng Văn Hùng', 'Bùi Thị Lan', 'Ngô Văn Nam', 'Đỗ Thị Phượng', 'Hoàng Văn Quý'
        ];
        $methods = ['Chuyển khoản', 'Tiền mặt', 'Thẻ tín dụng'];
        $statuses = ['paid', 'pending', 'cancel'];

        for ($i = 1; $i <= 50; $i++) {
            DB::table('invoices')->insert([
                'code' => '#INV-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'customer' => $customers[array_rand($customers)],
                'staff' => 'Admin',
                'date' => date('Y-m-d', strtotime('-' . rand(0, 60) . ' days')),
                'amount' => rand(2, 50) * 100000,
                'method' => $methods[array_rand($methods)],
                'status' => $statuses[array_rand($statuses)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
