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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('address')->nullable();
            $table->string('avatar')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1: Hoạt động, 0: Không hoạt động');
            $table->string('package_name');
            $table->integer('package_duration')->comment('Số tháng đăng ký');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('absent_days')->default(0)->comment('Số ngày vắng mặt');
            $table->tinyInteger('warning_level')->default(0)->comment('0: Không cảnh cáo, 1: Cảnh cáo lần 1, 2: Cảnh cáo lần 2');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
