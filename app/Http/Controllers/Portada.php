<?php

namespace App\Http\Controllers;
use App\Models\Report;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
class Portada extends Controller
{
    public function index()
    {
        // Pasar los datos a la vista PDF
        $reports = Report::find(1);

        $pdf = Pdf::loadView('plantillas.portada', compact('reports'));

        // Mostrar PDF en el navegador
        return $pdf->stream('portada.pdf');

        // Si lo quisieras descargar:
        // return $pdf->download('portada.pdf');
    }
}
