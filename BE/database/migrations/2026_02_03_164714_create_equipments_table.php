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
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            $table->string('ten_thiet_bi');
            $table->bigInteger('type_id');      // loại thiết bị
            $table->bigInteger('supplier_id');  // nhà cung cấp
            $table->bigInteger('room_id');      // phòng
            $table->integer('tinh_trang')->comment('1:Tốt, 2:Cần bảo trì, 3:Hỏng');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};
