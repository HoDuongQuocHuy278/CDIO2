<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CheckInSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('check_ins')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $memberIds = DB::table('members')->pluck('id')->toArray();
        if (empty($memberIds)) return;

        $types = ['FaceID', 'QR Code', 'Manual'];
        
        for ($i = 1; $i <= 100; $i++) {
            DB::table('check_ins')->insert([
                'member_id' => $memberIds[array_rand($memberIds)],
                'check_in_type' => $types[array_rand($types)],
                'status' => 1,
                'created_at' => \Carbon\Carbon::now()->subDays(rand(0, 7))->subHours(rand(0, 23))->subMinutes(rand(0, 59)),
                'updated_at' => now(),
            ]);
        }
    }
}
