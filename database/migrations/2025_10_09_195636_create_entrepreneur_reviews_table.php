<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// 20_create_entrepreneur_reviews_table.php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entrepreneur_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entrepreneur_id')->constrained('entrepreneurs')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('rating')->unsigned();
            $table->text('comment')->nullable();
            $table->timestamp('reviewed_at')->useCurrent();
            $table->timestamps();
            
            $table->unique(['entrepreneur_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entrepreneur_reviews');
    }
};
