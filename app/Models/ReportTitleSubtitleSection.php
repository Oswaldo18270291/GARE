<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportTitleSubtitleSection extends Model
{
    /** @use HasFactory<\Database\Factories\ReportTitleSubtitleSectionFactory> */
    use HasFactory;
    protected $fillable = ['r_t_s_id', 'section_id','status','content_id'];

        public function reportTitleSubtitle()
    {
        return $this->belongsTo(ReportTitleSubtitle::class, 'r_t_s_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function contents()
    {
        return $this->hasMany(Content::class, 'r_t_s_s_id');
    }
}
