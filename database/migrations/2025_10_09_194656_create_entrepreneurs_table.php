<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entrepreneurs', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone', 20)->nullable();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->boolean('verified')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamp('registered_at')->useCurrent();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entrepreneurs');
    }
};
