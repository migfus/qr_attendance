<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('request_status_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('hero_icon_id');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('request_status_types');
    }
};
