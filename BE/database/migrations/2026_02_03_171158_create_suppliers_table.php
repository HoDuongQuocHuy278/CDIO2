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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('ten_nha_cung_cap');
            $table->string('so_dien_thoai')->nullable()->unique()->index();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('dia_chi')->nullable();
            $table->integer('tinh_trang')->comment('1:Hoạt Động, 2:Hết Cung Cấp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
