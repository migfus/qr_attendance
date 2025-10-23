<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->ulid('user_id');
            $table->foreign('user_id')
                ->on('users')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->string('name');
            $table->string('image_url');
            $table->integer('quantity')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('inventories');
    }
};
