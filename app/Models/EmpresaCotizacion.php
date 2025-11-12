<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaCotizacion extends Model
{
    /** @use HasFactory<\Database\Factories\EmpresaCotizacionFactory> */
    use HasFactory;
    protected $fillable = ['content_id', 'nombre', 'color','orden'];

    public function detalles()
    {
        return $this->hasMany(DetalleCotizacion::class, 'empresa_id');
    }

    public function content()
    {
        return $this->belongsTo(Content::class);
    }
}
