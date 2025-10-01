<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalysisDiagrams extends Model
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
        'contet_id',   // <- coincide con tu migración
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

    /**
     * Relación: pertenece a un Content
     * (FK no convencional: contet_id)
     */
    public function content()
    {
        return $this->belongsTo(Content::class, 'contet_id');
    }
}
