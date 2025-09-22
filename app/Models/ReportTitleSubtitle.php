<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportTitleSubtitle extends Model
{
    /** @use HasFactory<\Database\Factories\ReportTitleSubtitleFactory> */
    use HasFactory;
    
    protected $fillable = ['r_t_id', 'subtitle_id','status'];


        public function reportTitle()
    {
        return $this->belongsTo(ReportTitle::class, 'r_t_id');
    }

        public function subtitle()
    {
        return $this->belongsTo(Subtitle::class);
    }

    public function reportTitleSubtitleSections()
    {
        return $this->hasMany(ReportTitleSubtitleSection::class, 'r_t_s_id');
    }
        public function contents()
    {
        return $this->hasMany(Content::class);
    }
}
