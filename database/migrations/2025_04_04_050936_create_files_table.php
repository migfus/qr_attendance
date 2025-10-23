<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('fileable_id');
            $table->string('fileable_type');
            $table->string('file_url');
            $table->unsignedBigInteger('file_type_id');
            $table->foreign('file_type_id')
                ->on('file_types')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('size')->default(0);
            $table->unsignedInteger('width')->default(0);
            $table->unsignedInteger('height')->default(0);
            $table->string('thumbnail_url');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('files');
    }
};
