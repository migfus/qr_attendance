<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('sort_id');
            $table->unsignedBigInteger('system_setting_category_id');
            $table->unsignedBigInteger('system_setting_type_id');
            $table->string('name');
            $table->string('description');
            $table->string('value');

            $table->foreign('system_setting_category_id')
                ->references('id')
                ->on('system_setting_categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('system_setting_type_id')
                ->references('id')
                ->on('system_setting_types')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('system_settings');
    }
};
