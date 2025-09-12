<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    /** @use HasFactory<\Database\Factories\ReportFactory> */
    use HasFactory;
    protected $fillable = [
        'nombre_empresa', 'giro_empresa', 'ubicacion', 'telefono', 'representante',
        'fecha_analisis', 'user_id', 'colaborador1', 'colaborador2',
        'colaborador3', 'colaborador4', 'colaborador5', 'logo', 'img'
    ];


        public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reportTitles()
    {
        return $this->hasMany(ReportTitle::class);
    }
}
