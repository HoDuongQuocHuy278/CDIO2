<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phan_quyen', function (Blueprint $table) {
            $table->id();
            $table->string('ten_chuc_vu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phan_quyen');
    }
};
