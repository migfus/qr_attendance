<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('attendees', function (Blueprint $table) {
            // $table->ulid('id')->primary()->unique();
            $table->id();

            $table->ulid('user_id');
            $table->foreign('user_id')
                ->on('users')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->ulid('host_user_id');
            $table->foreign('host_user_id')
                ->on('users')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->ulid('scanner_user_id')->nullable();
            $table->foreign('scanner_user_id')
                ->on('users')
                ->references('id');

            $table->ulid('attendance_id');
            $table->foreign('attendance_id')
                ->on('attendances')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->dateTime('scanned_date_time')->nullable(); // if null that's mean he/she didn't attend
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('attendees');
    }
};
