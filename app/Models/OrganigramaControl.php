<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganigramaControl extends Model
{
    /** @use HasFactory<\Database\Factories\OrganigramaControlFactory> */
    use HasFactory;
    protected $fillable = [
        'no',
        'riesgo',
        'content_id',
        'medidas_p',
        'acciones_planes',
        'status',
    ];


        public function content()
    {
        return $this->belongsTo(Content::class);
    }
}
