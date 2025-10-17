<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtitle extends Model
{
    /** @use HasFactory<\Database\Factories\SubtitleFactory> */
    use HasFactory;

    protected $fillable = ['nombre', 'title_id','orden'];

    
    public function title()
    {
        return $this->belongsTo(Title::class);
    }
        public function sections()
    {
        return $this->hasMany(Section::class);
    }
    
        public function reportTitleSubtitles()
    {
        return $this->hasMany(ReportTitleSubtitle::class);
    }
}
