<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('employees', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('mid_name')->nullable();

            $table->unsignedBigInteger('extra_name_id');
            $table->foreign('extra_name_id')
                ->on('extra_names')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('position_id');
            $table->foreign('position_id')
                ->on('positions')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('department_id');
            $table->foreign('department_id')
                ->on('departments')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('employee_status_id');
            $table->foreign('employee_status_id')
                ->on('employee_statuses')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('claim_type_id');
            $table->foreign('claim_type_id')
                ->on('claim_types')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('email');
            $table->string('password');

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('employees');
    }
};
