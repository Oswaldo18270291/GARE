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
            $table->string('giro_empresa')->nullable();;
            $table->string('ubicacion')->nullable();;
            $table->string('telefono')->nullable();;
            $table->string('representante');
            $table->date('fecha_analisis');
            $table->foreignId('user_id')->constrained('users'); //Creada
            $table->string('colaborador1');
            $table->string('colaborador2')->nullable();;
            $table->string('colaborador3')->nullable();;
            $table->string('colaborador4')->nullable();;
            $table->string('colaborador5')->nullable();;
            $table->string('logo')->nullable();
            $table->string('img')->nullable();
            $table->boolean('status')->default(false);
            $table->string('Folio')->nullable();
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
