<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    /** @use HasFactory<\Database\Factories\SectionFactory> */
    use HasFactory;
    protected $fillable = ['nombre', 'subtitle_id','orden','status'];

        public function subtitle()
    {
        return $this->belongsTo(Subtitle::class);
    }
            public function reportTitleSubtitleSections()
    {
        return $this->hasMany(ReportTitleSubtitleSection::class, 'r_t_s_id');
    }
}
