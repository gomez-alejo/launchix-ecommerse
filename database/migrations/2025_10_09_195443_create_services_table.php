<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// 08_create_services_table.php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entrepreneur_id')->constrained('entrepreneurs')->onDelete('cascade');
            $table->foreignId('service_category_id')->constrained('service_categories')->onDelete('restrict');
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->decimal('price_from', 10, 2);
            $table->boolean('available')->default(true);
            $table->string('business_hours')->nullable();
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};