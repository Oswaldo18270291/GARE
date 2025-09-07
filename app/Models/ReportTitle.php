<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportTitle extends Model
{
    /** @use HasFactory<\Database\Factories\ReportTitleFactory> */
    use HasFactory;

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
        return $this->hasMany(ReportTitleSubtitle::class);
    }
}
