<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    /** @use HasFactory<\Database\Factories\TitleFactory> */
    use HasFactory;

    protected $fillable = ['nombre','orden','status'];
    

        public function subtitles()
    {
        return $this->hasMany(Subtitle::class)
            ->where('status',true);
    }
        public function reportTitles()
    {
        return $this->hasMany(ReportTitle::class);
    }
}
