<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * ⭐ MIGRACIÓN CONSOLIDADA - Incluye campos de metadata
     * Consolida contenido de: improve_product_images_table.php
     */
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->string('image_url', 255);
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');

            // ⭐ CONSOLIDADO: Campos agregados por improve_product_images_table
            $table->string('alt_text', 255)->nullable();
            $table->boolean('is_primary')->default(false);
            $table->unsignedTinyInteger('display_order')->default(0);
            $table->unsignedInteger('file_size')->nullable(); // En bytes
            $table->string('mime_type', 100)->nullable();

            $table->timestamps();

            // ⭐ CONSOLIDADO: Índices agregados por improve_product_images_table
            $table->index('is_primary');
            $table->index('display_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
