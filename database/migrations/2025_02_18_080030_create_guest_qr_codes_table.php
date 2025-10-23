<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('guest_qr_codes', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->ulid('guest_id');
            $table->string('name');
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('status_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('guest_qr_codes');
    }
};
