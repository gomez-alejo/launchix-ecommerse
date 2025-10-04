<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
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
            $table->unsignedBigInteger('user_id')->nullable(); // Si usas autenticación
            $table->timestamps();
            
            // Si usas autenticación, descomenta esta línea:
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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