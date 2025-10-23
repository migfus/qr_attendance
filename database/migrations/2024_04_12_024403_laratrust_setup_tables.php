<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LaratrustSetupTables extends Migration {
    public function up(): void {

        Schema::create('permission_modules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('permission_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });


        // Create table for storing roles
        Schema::create('roles', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name')->unique();
            $table->string('icon_name');
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create table for storing permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->unsignedBigInteger('permission_module_id');
            $table->foreign('permission_module_id')->references('id')->on('permission_modules')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('permission_type_id');
            $table->foreign('permission_type_id')->references('id')->on('permission_modules')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->string('name')->unique();
            $table->string('display_name')->nullable(); // [All, Owner]
            $table->string('description')->nullable();

            $table->timestamps();
        });

        // Create table for associating roles to users and teams (Many To Many Polymorphic)
        Schema::create('role_user', function (Blueprint $table) {
            $table->ulid('role_id');
            $table->ulid('user_id');
            $table->string('user_type');

            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'role_id', 'user_type']);
        });

        // Create table for associating permissions to users (Many To Many Polymorphic)
        Schema::create('permission_user', function (Blueprint $table) {
            $table->ulid('permission_id');
            $table->ulid('user_id');
            $table->string('user_type');

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'permission_id', 'user_type']);
        });

        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create('permission_role', function (Blueprint $table) {
            $table->ulid('permission_id');
            $table->ulid('role_id');

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('permission_user');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');

        Schema::dropIfExists('permission_modules');
        Schema::dropIfExists('permission_types');
    }
}
