<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCotizacion extends Model
{
    /** @use HasFactory<\Database\Factories\DetalleCotizacionFactory> */
    use HasFactory;

    protected $fillable = ['empresa_id', 'concepto', 'cantidad', 'costo', 'comentarios','orden'];

    public function empresa()
    {
        return $this->belongsTo(EmpresaCotizacion::class);
    }
}
