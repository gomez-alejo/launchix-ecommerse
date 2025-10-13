<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * ⭐ MIGRACIÓN CONSOLIDADA - Incluye todas las optimizaciones
     * Consolida contenido de: optimize_users_table.php
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Campos adicionales del usuario
            $table->string('phone', 20)->nullable();
            $table->date('birthdate')->nullable();
            $table->string('main_address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('department')->nullable();
            $table->timestamp('registered_at')->useCurrent();

            // ⭐ CONSOLIDADO: Campos agregados por optimize_users_table
            $table->string('avatar')->nullable();
            $table->timestamp('phone_verified_at')->nullable();

            $table->rememberToken();
            $table->timestamps();

            // ⭐ CONSOLIDADO: Soft deletes agregado por optimize_users_table
            $table->softDeletes();

            // ⭐ CONSOLIDADO: Índices agregados por optimize_users_table
            $table->index('city');
            $table->index('department');
            $table->index('username');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
