<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('attendances', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('author_id'); // user_id
            $table->foreign('author_id')
                ->on('users')
                ->references('id')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('department_id');
            $table->foreign('department_id')
                ->on('departments')
                ->references('id')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('name');
            $table->dateTimeTz('start_date_time');
            $table->dateTimeTz('end_date_time');
            $table->dateTimeTz('forced_closed_date_time')->nullable();
            $table->string('description');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('attendances');
    }
};
