<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MentalMap extends Model
{
    protected $fillable = [
        'content_id',
        'nodos',
        'relaciones',
        'background_image',
        'background_opacity'
    ];

    protected $casts = [
        'nodos' => 'array',
        'relaciones' => 'array',
    ];

    public function content()
    {
        return $this->belongsTo(Content::class);
    }
}
