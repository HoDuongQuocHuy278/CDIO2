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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('ho_ten');
            $table->date('ngay_sinh');
            $table->string('sdt');
            $table->string('dia_chi');
            $table->string('email')->unique();
            $table->string('chuc_vu');
            $table->integer('luong');
            $table->date('ngay_vao_lam');
            $table->tinyInteger('trang_thai')->default(1)->comment('0: Nghỉ Làm, 1: đang làm, 2:tạm nghỉ');
            $table->integer('user_id')->nullable();

            // $table->unsignedBigInteger('user_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
