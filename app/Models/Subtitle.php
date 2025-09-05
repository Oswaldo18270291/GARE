<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtitle extends Model
{
    /** @use HasFactory<\Database\Factories\SubtitleFactory> */
    use HasFactory;


    
    public function titles()
    {
        return $this->belongsTo(Title::class);
    }
}
