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
        Schema::create('mental_maps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained()->onDelete('cascade');
            $table->json('nodos')->nullable();       // 🔹 Nodos con sus propiedades
            $table->json('relaciones')->nullable();  // 🔹 Conexiones (edges)
            $table->longText('background_image')->nullable(); // base64
            $table->float('background_opacity')->default(0.4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mental_maps');
    }
};
