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
        Schema::create('analysis_diagrams', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('no')->nullable();
            $table->string('riesgo')->nullable();
            $table->integer('impacto_f')->nullable();
            $table->integer('impacto_o')->nullable();
            $table->integer('extension_d')->nullable();
            $table->integer('probabilidad_m')->nullable();
            $table->integer('impacto_fin')->nullable();
            $table->integer('cal')->nullable();
            $table->string('clase_riesgo')->nullable();
            $table->string('factor_oc')->nullable();

            $table->integer('f')->nullable();
            $table->integer('s')->nullable();
            $table->integer('p')->nullable();
            $table->integer('e')->nullable();
            $table->integer('pb')->nullable();
            $table->integer('if')->nullable();
            $table->double('f_ocurrencia')->nullable();
            $table->foreignId('content_id')
                ->nullable()
                ->constrained('contents')
                ->onDelete('cascade');
            $table->integer('orden')->default(0);
            $table->string('tipo_riesgo')->default('pendientes');
            $table->integer('orden2')->default(0);
            $table->string('c_riesgo')->default('pendientes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analysis_diagrams');
    }
};
