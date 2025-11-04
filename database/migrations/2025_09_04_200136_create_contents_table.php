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
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // Llaves foráneas con eliminación en cascada
            $table->foreignId('r_t_id')
                ->nullable()
                ->constrained('report_titles')
                ->onDelete('cascade');

            $table->foreignId('r_t_s_id')
                ->nullable()
                ->constrained('report_title_subtitles')
                ->onDelete('cascade');

            $table->foreignId('r_t_s_s_id')
                ->nullable()
                ->constrained('report_title_subtitle_sections')
                ->onDelete('cascade');

            // Contenido y multimedia
            $table->text('cont')->nullable();
            $table->string('img1')->nullable();
            $table->string('leyenda1')->nullable();
            $table->string('img2')->nullable();
            $table->string('leyenda2')->nullable();
            $table->string('img3')->nullable();
            $table->string('leyenda3')->nullable();
            $table->string('grafica')->nullable();
            $table->string('img_grafica')->nullable();
            /* $table->text('que')->nullable();
            $table->text('como')->nullable();
            $table->text('quien')->nullable();
            $table->text('por_que')->nullable();
            $table->text('donde')->nullable();
            $table->string('cuanto')->nullable();
            $table->date('de')->nullable();
            $table->date('hasta')->nullable(); */
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
