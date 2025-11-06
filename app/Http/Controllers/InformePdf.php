<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Report;
use App\Models\ReportTitle;
use App\Models\ReportTitleSubtitle;
use App\Models\ReportTitleSubtitleSection;
use App\Models\AnalysisDiagram;

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
            $title->content = Content::where('r_t_id', $title->id)->get();
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

        public function generarGrafica($id)
    {
        $report = Report::findOrFail($id);
        $ti = ReportTitle::where('report_id', $report->id)->where('title_id', 4 )             
        ->where('status', 1)
        ->first();
        $su = ReportTitleSubtitle::where('r_t_id', $ti->id)->where('subtitle_id', 32 )     
        ->where('status', 1)->first();

        $co = Content::where('r_t_s_id', $su->id)->first();
        if(!empty($co)){

        $risks = AnalysisDiagram::where('content_id',$co->id)->orderBy('no')->get();
        $grafica = $co->grafica ?? 'bar';
        // 👉 Esta vista no se mostrará al usuario, solo genera la gráfica en background
        return view('reports.generar_grafica', compact('report', 'risks', 'grafica'));
        }else{
           return redirect()->route('reporte.pdf', ['id' => $id]);
        }

        
        /*
        $ti2 = ReportTitle::where('report_id', $report->id)->where('title_id', 4 )             
        ->where('status', 1)
        ->first();
        $su2 = ReportTitleSubtitle::where('r_t_id', $ti2->id)->where('subtitle_id', 16 )     
        ->where('status', 1)->first();
        $co2 = Content::where('r_t_s_id', $su2->id)->first();
        */



    }

    public function guardarImagenGrafica(Request $request, $id)
    {

    $report = Report::findOrFail($id);
    
    $ti = ReportTitle::where('report_id', $report->id)->where('title_id', 4 )             
    ->where('status', 1)
    ->first();

    $su = ReportTitleSubtitle::where('r_t_id', $ti->id)->where('subtitle_id', 32 )     
    ->where('status', 1)->first();
    $content = Content::where('r_t_s_id', $su->id)->first(); // o según tu relación

    $base64 = $request->imagen;
    $nombre = 'grafica_reporte_'.$id.'.png';
    $ruta = 'graficas/'.$nombre;

    // Eliminar la anterior si existe
    if (Storage::exists($ruta)) {
        Storage::delete($ruta);
    }

    // Guardar nueva
    $imagen = str_replace('data:image/png;base64,', '', $base64);
    Storage::disk('public')->put('graficas/'.$nombre, base64_decode($imagen));

    // Actualizar en BD
    $content->update(['img_grafica' => 'graficas/'.$nombre]);

    return response()->json(['success' => true]);
    }

}
