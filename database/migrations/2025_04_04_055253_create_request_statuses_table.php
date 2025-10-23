<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('request_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_status_type_id');
            $table->foreign('request_status_type_id')
                ->on('request_status_types')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->ulid('employee_id');
            $table->foreign('employee_id')
                ->on('employees')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->ulid('user_id')->nullable();
            $table->foreign('user_id')
                ->on('users')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->longText('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('request_statuses');
    }
};
