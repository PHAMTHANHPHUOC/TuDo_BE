<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('thong_tin_thanh_toans', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('so_dien_thoai');
            $table->string('ho_va_ten');
            $table->integer('is_active')->default(0);
            $table->integer('has_active')->default(0);
            $table->integer('pin_active')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thong_tin_thanh_toans');
    }
};
