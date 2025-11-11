<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Schema;
use App\Models\Content;
use App\Models\Report;
use App\Models\ReportTitle;
use App\Models\ReportTitleSubtitle;
use App\Models\ReportTitleSubtitleSection;
use App\Models\AnalysisDiagram;
use App\Models\MentalMap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Barryvdh\DomPDF\Facade\Pdf;
use setasign\Fpdi\PdfParser\StreamReader as PdfParserStreamReader;
use setasign\Fpdi\Tcpdf\Fpdi;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Smalot\PdfParser\Parser; // 👈 para leer el PDF
use Illuminate\Support\Facades\Log;
class InformePdf extends Controller
{
        public $reports;
        public $ti;
        public $su;
        public $co;
        public $ti2;
        public $su2;
        public $co2;
        public $diagrama;
        public $mapa_m;

    use AuthorizesRequests;

    public function generar($id)
    {
        $this->reports = Report::findOrFail($id);

        $report = Report::findOrFail($id);
        $this->authorize('update', $report);

        // 🔹 Cargar estructura completa
        $report->titles = ReportTitle::
            with('title')
            ->join('titles', 'report_titles.title_id','=','titles.id')
            ->select('titles.*','report_titles.*')
            ->where('report_titles.report_id', $report->id)
            ->where('report_titles.status', 1)
            ->orderBy('titles.orden','asc')
            ->get();

        foreach ($report->titles as $title) {
            $title->content = Content::with([
                'cotizaciones.detalles' // ✅ carga empresas y detalles
                ])
                ->where('r_t_id', $title->id)
                ->orderBy('bloque_num')
                ->get();
            $title->subtitles = ReportTitleSubtitle::
            with('subtitle')
            ->join('subtitles', 'report_title_subtitles.subtitle_id','=','subtitles.id')
            ->select('subtitles.*','report_title_subtitles.*')
            ->where('report_title_subtitles.r_t_id', $title->id)
            ->where('report_title_subtitles.status', 1)
            ->orderBy('subtitles.orden','asc')
            ->get();

            foreach ($title->subtitles as $subtitle) {
                $subtitle->content = Content::where('r_t_s_id', $subtitle->id)->get();
                $subtitle->sections = ReportTitleSubtitleSection::with('section')
                ->join('sections', 'report_title_subtitle_sections.section_id','=','sections.id')
                ->select('sections.*','report_title_subtitle_sections.*')
                ->orderBy('sections.orden','asc')
                ->where('report_title_subtitle_sections.r_t_s_id', $subtitle->id)
                ->where('report_title_subtitle_sections.status', 1)
                ->get();
                

                foreach ($subtitle->sections as $section) {
                    $section->content = Content::where('r_t_s_s_id', $section->id)->get();
                }
            }
        }

        $ti = ReportTitle::where('report_id', $report->id)->where('title_id', 4 )             
        ->where('status', 1)
        ->first();

        $su = ReportTitleSubtitle::where('r_t_id', $ti->id)->where('subtitle_id', 32 )     
        ->where('status', 1)->first();
        $co = Content::where('r_t_s_id', $su->id)->first();

        if(!empty($co)){
            $diagrama = AnalysisDiagram::where('content_id', $co->id)->get();        
            $pdfContenido = Pdf::loadView('plantillas.contenido', ['reports' => $report, 'diagrama'=>$diagrama]);
        }else{
            $pdfContenido = Pdf::loadView('plantillas.contenido', ['reports' => $report]);
        }
        //$co = Content::where('r_t_s_id', $su->id)->first();

        // 🔹 Generar contenido con marcadores invisibles
        $pathContenido = storage_path("app/public/tmp_contenido_{$id}.pdf");
        file_put_contents($pathContenido, $pdfContenido->output());

        // 🔹 Analizar el PDF con Smalot\PdfParser
        $parser = new Parser();
        $pdf = $parser->parseFile($pathContenido);

        $markers = [];
        $pageNumber = 1;
        foreach ($pdf->getPages() as $page) {
            $text = $page->getText();

            // Detectar títulos
            foreach ($report->titles as $title) {
                if (strpos($text, "__MARKER_TITLE_{$title->id}__") !== false) {
                    $markers["TITLE_{$title->id}"] = $pageNumber;
                }

                // Detectar subtítulos
                foreach ($title->subtitles as $subtitle) {
                    if (strpos($text, "__MARKER_SUBTITLE_{$subtitle->id}__") !== false) {
                        $markers["SUBTITLE_{$subtitle->id}"] = $pageNumber;
                    }

                    // Detectar secciones
                    foreach ($subtitle->sections as $section) {
                        if (strpos($text, "__MARKER_SECTION_{$section->id}__") !== false) {
                            $markers["SECTION_{$section->id}"] = $pageNumber;
                        }
                    }
                }
            }
            $pageNumber++;
        }

        // 🔹 Generar las demás plantillas
        $pdfPortada = Pdf::loadView('plantillas.portada',  [
            'reports' => $report,
        ])->output();
        $pdfColaboradores = Pdf::loadView('plantillas.colaboradores', [
            'reports' => $report,
        ])->output();

        // 🔹 Pasamos los markers al índice
        $pdfIndice = Pdf::loadView('plantillas.indice', [
            'reports' => $report,
            'markers' => $markers
        ])->output();

        // 🔹 Fusionar todos los PDFs con FPDI
        $pdf = new class extends Fpdi {
            public $pageNumber = 0;
            public $totalPages = 0;
            public $paginar = false;

            public function Footer()
            {
                if ($this->paginar && $this->pageNumber > 0) {
                    $this->SetY(-15);
                    $this->SetFont('helvetica', '', 10);
                    $this->Cell(0, 10, 'Página ' . $this->pageNumber . ' de ' . $this->totalPages, 0, 0, 'C');
                }
            }
        };

        $pdf->SetCreator('Laravel');
        $pdf->SetAuthor('SSP');
        $pdf->SetTitle('Informe ' . $report->id);
        $pdf->SetMargins(15, 15, 15);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);

        $allDocs = [
            ['doc' => $pdfPortada, 'paginar' => false],
            ['doc' => $pdfColaboradores, 'paginar' => false],
            ['doc' => $pdfIndice, 'paginar' => false],
            ['doc' => file_get_contents($pathContenido), 'paginar' => true],
        ];

        foreach ($allDocs as $item) {
            $pdf->paginar = $item['paginar'];
            $pageCount = $pdf->setSourceFile(PdfParserStreamReader::createByString($item['doc']));

            if ($pdf->paginar) {
                $pdf->totalPages = $pageCount;
                $pdf->pageNumber = 0;
            }

            for ($page = 1; $page <= $pageCount; $page++) {
                $tpl = $pdf->importPage($page);
                $size = $pdf->getTemplateSize($tpl);

                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($tpl);
                if ($pdf->paginar) $pdf->pageNumber++;
            }
        }

        $filename = "Informe_{$report->id}.pdf";
        return response($pdf->Output($filename, 'S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "inline; filename=\"$filename\"");
    }

 public function generarGraficas($id)
{
    $report = Report::findOrFail($id);

    $ti = ReportTitle::where('report_id', $report->id)
        ->where('title_id', 4)
        ->where('status', 1)
        ->first();

    if (!$ti) {
        return redirect()->route('reporte.pdf', ['id' => $id]);
    }

    // 🔹 Subtítulo 32
    $su32 = ReportTitleSubtitle::where('r_t_id', $ti->id)
        ->where('subtitle_id', 32)
        ->where('status', 1)
        ->first();

    $co32 = $su32 ? Content::where('r_t_s_id', $su32->id)->first() : null;

    // 🔹 Subtítulo 16
    $su16 = ReportTitleSubtitle::where('r_t_id', $ti->id)
        ->where('subtitle_id', 16)
        ->where('status', 1)
        ->first();

    $co16 = $su16 ? Content::where('r_t_s_id', $su16->id)->first() : null;
    // 🔹 Preparamos los datos de ambas gráficas
    $graficas = [];

    if ($co32) {
        $graficas[] = [
            'subtitleId' => 32,
            'risks' => AnalysisDiagram::where('content_id', $co32->id)->orderBy('no')->get(),
            'tipo' => $co32->grafica ?? 'bar',
        ];
    }

    if ($co16) {
        $graficas[] = [
            'subtitleId' => 16,
            'risks' => AnalysisDiagram::where('content_id', $co32->id)->orderBy('no')->get(),
            'tipo' => $co16->grafica ?? 'bar',
        ];
    }

    if (empty($graficas)) {
        return redirect()->route('reporte.pdf', ['id' => $id]);
    }

    return view('reports.generar_grafica', compact('report', 'graficas'));
}


public function guardarImagenGrafica(Request $request, $id)
{
    $subtitleId = (int) $request->input('subtitleId', 32);

    $report = Report::findOrFail($id);

    $ti = ReportTitle::where('report_id', $report->id)
        ->where('title_id', 4)
        ->where('status', 1)
        ->first();

    $su = ReportTitleSubtitle::where('r_t_id', $ti->id)
        ->where('subtitle_id', $subtitleId)
        ->where('status', 1)
        ->first();

    $content = Content::where('r_t_s_id', $su->id)->first();
    if (!$content) {
        return response()->json(['error' => 'Contenido no encontrado'], 404);
    }

    $base64 = $request->imagen;
    $nombre = "grafica_reporte_{$id}_sub{$subtitleId}.png";
    $ruta   = "graficas/{$nombre}";

    if (Storage::disk('public')->exists($ruta)) {
        Storage::disk('public')->delete($ruta);
    }

    $imagen = str_replace('data:image/png;base64,', '', $base64);
    Storage::disk('public')->put($ruta, base64_decode($imagen));

    // Puedes usar un solo campo (img_grafica) o separar por subtítulo:
    // $campo = $subtitleId == 16 ? 'img_grafica_16' : 'img_grafica_32';
    // $content->update([$campo => $ruta]);

    // genérico (si solo tienes un campo):
    // $content->update(['img_grafica' => $ruta]);

    // Recomendado: si quieres almacenar por subtítulo, asegúrate de tener ambas columnas:
    if (Schema::hasColumn('contents', 'img_grafica_16') && Schema::hasColumn('contents', 'img_grafica_32')) {
        $campo = $subtitleId == 16 ? 'img_grafica_16' : 'img_grafica_32';
        $content->update([$campo => $ruta]);
    } else {
        // fallback genérico
        $content->update(['img_grafica' => $ruta]);
    }

    return response()->json(['success' => true, 'ruta' => $ruta]);
}




    public $nodos=[];
    public $relaciones;
    public $background_image;
    public $background_opacity;

    public function generarMapa($id)
{

    $report = Report::findOrFail($id);

    // 🔹 Buscar el contenido correspondiente al mapa mental
    $ti = ReportTitle::where('report_id', $report->id)
        ->where('title_id', 4)
        ->where('status', 1)
        ->first();

    $su = ReportTitleSubtitle::where('r_t_id', $ti->id)
        ->where('subtitle_id', 32)
        ->where('status', 1)
        ->first();

    $content = Content::where('r_t_s_id', $su->id)->first();

    if (!$content) {
        return redirect()->route('reporte.pdf', ['id' => $id]);
    }else{
        $mapa_m = MentalMap::where('content_id',$content->id)->first();
    }

    // 🔹 Datos del mapa mental
    $nodos = $mapa_m->nodos ?? '[]';
    $relaciones = $mapa_m->relaciones ?? '[]';
    $background_image = $mapa_m->background_image ?? null;
    $background_opacity = $mapa_m->background_opacity ?? 0.4;

    return view('reports.generar_mapa', compact(
        'report',
        'nodos',
        'relaciones',
        'background_image',
        'background_opacity'
    ));
}

public function guardarImagenMapa(Request $request, $id)
{
   
    $report = Report::findOrFail($id);

    $ti = ReportTitle::where('report_id', $report->id)
        ->where('title_id', 4)
        ->where('status', 1)
        ->first();

    $su = ReportTitleSubtitle::where('r_t_id', $ti->id)
        ->where('subtitle_id', 32)
        ->where('status', 1)
        ->first();

    $content = Content::where('r_t_s_id', $su->id)->first();

    if (!$content) {
        return response()->json(['error' => 'Contenido no encontrado'], 404);
    }

    $base64 = $request->imagen;
    $nombre = 'mapa_reporte_' . $id . '.png';
    $ruta = 'mapas/' . $nombre;

    // Eliminar anterior si existe
    if (Storage::disk('public')->exists($ruta)) {
        Storage::disk('public')->delete($ruta);
    }

    // Guardar nueva imagen
    $imagen = str_replace('data:image/png;base64,', '', $base64);
    Storage::disk('public')->put($ruta, base64_decode($imagen));

    // Actualizar BD
    $content->update(['img_mapa' => $ruta]);

    return response()->json(['success' => true]);
}

public function descargar($id)
{
    $report = Report::findOrFail($id);

    if (!$report->pdf_path) {
        return back()->with('error', 'El PDF no está disponible.');
    }

    $filePath = storage_path('app/public/' . $report->pdf_path);

    if (!file_exists($filePath)) {
        return back()->with('error', 'El archivo PDF no existe en el servidor.');
    }

    return response()->download($filePath, basename($filePath));
}


}
