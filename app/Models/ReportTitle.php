<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportTitle extends Model
{
    use HasFactory;

    protected $fillable = ['report_id', 'title_id', 'status'];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function title()
    {
        return $this->belongsTo(Title::class);
    }

    public function reportTitleSubtitles()
    {
        return $this->hasMany(ReportTitleSubtitle::class, 'r_t_id');
    }

    public function contents()
    {
        return $this->hasMany(Content::class, 'r_t_id');
    }
}

