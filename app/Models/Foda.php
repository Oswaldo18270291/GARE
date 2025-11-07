<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foda extends Model
{
    /** @use HasFactory<\Database\Factories\FodaFactory> */
    use HasFactory;

    protected $fillable = [
        'content_id',
        'fortalezas',
        'debilidades',
        'oportunidades',
        'amenazas',
    ];

    public function content()
    {
        return $this->belongsTo(Content::class);
    }
}
