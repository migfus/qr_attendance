<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('quick_responses', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->ulid('employee_id')->index();
            $table->foreign('employee_id')
                ->on('employees')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('quick_responses');
    }
};
