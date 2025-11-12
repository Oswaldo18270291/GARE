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
        Schema::create('detalle_cotizacions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresa_cotizacions')->onDelete('cascade');
            $table->string('concepto');
            $table->string('cantidad')->nullable();
            $table->string('costo')->nullable();
            $table->string('comentarios')->nullable();
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_cotizacions');
    }
};
