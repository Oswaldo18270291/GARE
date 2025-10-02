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
            $table->string('no');
            $table->string('riesgo');
            $table->integer('f');
            $table->integer('s');
            $table->integer('p');
            $table->integer('e');
            $table->integer('pb');
            $table->integer('if');
            $table->double('f_ocurrencia');
            $table->foreignId('content_id')
                ->nullable()
                ->constrained('contents')
                ->onDelete('cascade');

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
