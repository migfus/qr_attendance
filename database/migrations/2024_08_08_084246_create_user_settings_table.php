<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->ulid('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->json('config')->default(
                json_encode([
                    'themes' => 'Light',
                    'card' => [
                        'show_description' => true
                    ],
                    'notification' => [
                        'push' => [
                            'registered' => true,
                        ],
                        'email' => [
                            'registered' => false,
                        ],
                        'sms' => [
                            'registered' => true,
                        ],
                    ]
                ])
            );

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('user_settings');
    }
};
