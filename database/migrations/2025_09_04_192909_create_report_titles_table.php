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
        Schema::create('report_titles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('report_id')->constrained('reports'); //Creada
            $table->foreignId('title_id')->constrained('titles'); //Creada
            $table->boolean('status')->default(false);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_titles');
    }
};
