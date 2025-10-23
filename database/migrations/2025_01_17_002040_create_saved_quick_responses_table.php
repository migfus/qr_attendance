<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('saved_quick_responses', function (Blueprint $table) {
            $table->ulid('id')->primary()->index();
            $table->ulid('guest_id');
            $table->longText('qr_link');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('saved_quick_responses');
    }
};
