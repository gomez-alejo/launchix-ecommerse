<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// 04_create_entrepreneur_addresses_table.php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entrepreneur_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entrepreneur_id')->constrained('entrepreneurs')->onDelete('cascade');
            $table->string('address');
            $table->string('city');
            $table->string('department');
            $table->string('postal_code', 20)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('is_main')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entrepreneur_addresses');
    }
};

