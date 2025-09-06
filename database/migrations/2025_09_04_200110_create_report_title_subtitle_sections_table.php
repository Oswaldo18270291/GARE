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
        Schema::create('report_title_subtitle_sections', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('section_id')->constrained('sections'); //Creada
            $table->foreignId('r_t_s_id')->constrained('report_title_subtitles'); //Creada
            $table->boolean('status')->default(false);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_title_subtitle_sections');
    }
};
