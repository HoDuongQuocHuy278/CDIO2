<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->string('ma_the')->unique();
            $table->foreignId('khuyen_mai_id')->nullable()->constrained('promotions')->onDelete('set null');
            $table->date('ngay_cap');
            $table->date('ngay_het_han');
            $table->string('trang_thai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_cards');
    }
};
