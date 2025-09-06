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
            $table->foreignId('r_t_id')->constrained('report_titles'); //Creada
            $table->foreignId('r_t_s_id')->constrained('report_title_subtitles'); //Creada
            $table->foreignId('r_t_s_s_id')->constrained('report_title_subtitle_sections'); //Creada
            $table->string('cont');
            $table->string('img1');
            $table->string('img2');
            $table->string('img3');

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
