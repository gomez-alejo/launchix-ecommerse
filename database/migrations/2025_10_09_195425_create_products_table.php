<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// 07_create_products_table.php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entrepreneur_id')->constrained('entrepreneurs')->onDelete('cascade');
            $table->foreignId('product_category_id')->constrained('product_categories')->onDelete('restrict');
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            $table->integer('sales')->default(0);
            $table->boolean('available')->default(true);
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
