<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('system_setting_categories', function (Blueprint $table) {
            $table->id();
            $table->integer('sort_id')->unique();
            $table->string('name');
            $table->longText('icon');
            $table->string('description');
            $table->string('href');
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('system_setting_categories'); }
};
