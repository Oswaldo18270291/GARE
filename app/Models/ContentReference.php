<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentReference extends Model
{
   use HasFactory;

    protected $table = 'content_references';

    protected $fillable = [
        'content_id',
        'numero',
        'texto',
    ];

    /**
     * Relación: una referencia pertenece a un contenido.
     */
    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    public static function nextNumberForReport($reportId)
    {
        $max = self::whereHas('content', function ($q) use ($reportId) {
            $q->whereHas('reportTitle', fn($r) => $r->where('report_id', $reportId))
            ->orWhereHas('reportTitleSubtitle.reportTitle', fn($r) => $r->where('report_id', $reportId))
            ->orWhereHas('reportTitleSubtitleSection.reportTitleSubtitle.reportTitle', fn($r) => $r->where('report_id', $reportId));
        })->max('numero');

        return $max ? $max + 1 : 1;
    }
}
