<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccionSeguridad extends Model
{
    /** @use HasFactory<\Database\Factories\AccionSeguridadFactory> */
    use HasFactory;

    protected $fillable = [
        'content_id',
        'tit',
        'tema',
        'accion',
        't_costo',
        'nivel_p',
        'no'
    ];  
        public function content()
    {
        return $this->belongsTo(Content::class);
    }
}
