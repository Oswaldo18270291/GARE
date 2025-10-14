<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Dashboard extends Controller
{
    public function index()
    {
        // Obtener reportes agrupados por mes
        $reportesPorMes = Report::select(
                DB::raw('EXTRACT(MONTH FROM created_at) as mes'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes')
            ->toArray();

        // Etiquetas de meses en español
        $mesesEspanol = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        // Inicializar valores por mes (en orden)
        $totales = [];
        foreach (range(1, 12) as $mes) {
            $totales[] = $reportesPorMes[$mes] ?? 0;
        }

        $totalPublicos = Report::where('clasificacion', 'Público')->count();
        $totalConfidencial = Report::where('clasificacion', 'Confidencial')->count();
        $total = Report::count();
        $totalInformesFinalizados = Report::where('status', 1)->count();

        // Total del año
        $totalAnual = array_sum($reportesPorMes);
        $anioActual = date('Y');

        return view('dashboard', [
            'mesesEspanol' => array_values($mesesEspanol),
            'totales' => $totales,
            'anioActual' => $anioActual,
            'totalAnual' => $totalAnual,
            'totalPublicos' => $totalPublicos,
            'totalConfidencial' => $totalConfidencial,
            'total' => $total,
            'totalInformesFinalizados' => $totalInformesFinalizados,
        ]);
    }
}
