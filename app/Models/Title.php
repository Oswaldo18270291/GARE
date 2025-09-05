<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    /** @use HasFactory<\Database\Factories\TitleFactory> */
    use HasFactory;


    
    public function reports()
    {
        return $this->belongsToMany(Report::class);
    }
        public function subtitles()
    {
        return $this->hasMany(Subtitle::class);
    }
}
