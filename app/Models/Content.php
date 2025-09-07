<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    /** @use HasFactory<\Database\Factories\ContentFactory> */
    use HasFactory;

        protected $fillable = ['r_t_s_s_id', 'cont', 'img1', 'img2', 'img3'];

        public function reportTitleSubtitleSection()
    {
        return $this->belongsTo(ReportTitleSubtitleSection::class, 'r_t_s_s_id');
    }

        public function reportTitleSubtitle()
    {
        return $this->belongsTo(ReportTitleSubtitle::class, 'r_t_s_id');
    }

}
