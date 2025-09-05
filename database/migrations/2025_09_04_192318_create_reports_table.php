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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nombre_empresa');      
            $table->string('giro_empresa');
            $table->integer('ubicacion');
            $table->integer('telefono');
            $table->string('representante');
            $table->date('fecha_analisis');
            $table->foreignId('user_id')->constrained('users'); //Creada
            $table->string('colaborador1');
            $table->string('colaborador2');
            $table->string('colaborador3');
            $table->string('colaborador4');
            $table->string('colaborador5');
            $table->string('logo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
