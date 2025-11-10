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
        Schema::create('accion_seguridads', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('content_id')->constrained()->onDelete('cascade');
            $table->string('tema')->nullable();
            $table->text('accion')->nullable();
            $table->string('t_costo')->nullable();
            $table->string('nivel_p')->nullable();
            $table->integer('no')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accion_seguridads');
    }
};
