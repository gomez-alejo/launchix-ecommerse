<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * ⭐ MIGRACIÓN CONSOLIDADA - Incluye FK activada y optimizaciones
     * Consolida contenido de: improve_servicios_table.php
     */
    public function up(): void
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_servicio');
            $table->string('categoria');
            $table->text('descripcion');
            $table->string('direccion');
            $table->string('telefono', 20);
            $table->decimal('precio_base', 10, 2)->nullable();
            $table->string('horario_atencion')->nullable();
            $table->string('imagen_principal')->nullable();
            $table->json('galeria_imagenes')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            // ⭐ CONSOLIDADO: Campo agregado por improve_servicios_table
            $table->enum('status', ['active', 'inactive', 'draft'])->default('active');

            $table->timestamps();

            // ⭐ CONSOLIDADO: Soft deletes agregado por improve_servicios_table
            $table->softDeletes();

            // ⭐ CONSOLIDADO: Foreign key activada (estaba comentada en original)
            $table->foreign('user_id')->references('id')->on('entrepreneurs')->onDelete('cascade');

            // ⭐ CONSOLIDADO: Índices agregados por improve_servicios_table
            $table->index('categoria');
            $table->index('precio_base');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
