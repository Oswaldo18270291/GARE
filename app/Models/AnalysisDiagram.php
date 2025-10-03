<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalysisDiagram extends Model
{
use HasFactory;

    // Por convención, la tabla "analysis_diagrams" se detecta sola.
    // Si tuvieras otro nombre de tabla, usa protected $table = '...';

    protected $fillable = [
        'no',
        'riesgo',
        'f',
        's',
        'p',
        'e',
        'pb',
        'if',
        'f_ocurrencia',
        'content_id',
        'orden',
        'tipo_riesgo',
    ];

    protected $casts = [
        'f'            => 'integer',
        's'            => 'integer',
        'p'            => 'integer',
        'e'            => 'integer',
        'pb'           => 'integer',
        'if'           => 'integer',
        'f_ocurrencia' => 'float',
    ];


    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id');
    }
}
