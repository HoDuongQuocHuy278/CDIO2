<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('loai_dich_vu')->nullable();
            $table->double('gia_tien')->default(0);
            $table->string('thoi_han')->nullable();
            $table->string('so_buoi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['loai_dich_vu', 'gia_tien', 'thoi_han', 'so_buoi']);
        });
    }
};
