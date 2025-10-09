<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Report;
use App\Models\ReportTitle;
use App\Models\ReportTitleSubtitle;
use App\Models\ReportTitleSubtitleSection;
use App\Models\AnalysisDiagram;
use Barryvdh\DomPDF\Facade\Pdf;
use setasign\Fpdi\PdfParser\StreamReader as PdfParserStreamReader;
use setasign\Fpdi\Tcpdf\Fpdi;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InformePdf extends Controller
{
    use AuthorizesRequests;

    public $reports;
    public $contT;
    public $c;

    public function generar($id)
    {
        $this->reports = Report::findOrFail($id);
        $report = Report::findOrFail($id);
        $this->authorize('update', $report);

        // 🔹 Cargar títulos, subtítulos y secciones con contenido
        $this->reports->titles = ReportTitle::where('report_id', $this->reports->id)
            ->where('status', 1)
            ->get();

        foreach ($this->reports->titles as $title) {
            $title->content = Content::where('r_t_id', $title->id)->get();
            $title->subtitles = ReportTitleSubtitle::where('r_t_id', $title->id)
                ->where('status', 1)
                ->get();

            foreach ($title->subtitles as $subtitle) {
                $subtitle->content = Content::where('r_t_s_id', $subtitle->id)->get();
                $subtitle->sections = ReportTitleSubtitleSection::where('r_t_s_id', $subtitle->id)
                    ->where('status', 1)
                    ->get();

                foreach ($subtitle->sections as $section) {
                    $section->content = Content::where('r_t_s_s_id', $section->id)->get();
                }
            }
        }

        // 🔹 Generar PDFs individuales
        $pdfPortada = Pdf::loadView('plantillas.portada', [
            'reports' => $this->reports,
        ])->output();

        $pdfColaboradores = Pdf::loadView('plantillas.colaboradores', [
            'reports' => $this->reports,
        ])->output();

        $pdfIndice = Pdf::loadView('plantillas.indice', [
            'reports' => $this->reports,
        ])->output();

        $pdfContenido = Pdf::loadView('plantillas.contenido', [
            'reports' => $this->reports,
        ])->output();

        // 🔹 Clase personalizada con control de numeración
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
        $pdf->SetTitle('Informe ' . $this->reports->id);
        $pdf->SetMargins(15, 15, 15);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);

        // 🔹 Documentos a unir (solo paginar el contenido)
        $allDocs = [
            ['doc' => $pdfPortada, 'paginar' => false],
            ['doc' => $pdfColaboradores, 'paginar' => false],
            ['doc' => $pdfIndice, 'paginar' => false],
            ['doc' => $pdfContenido, 'paginar' => true],
        ];

        foreach ($allDocs as $item) {
            $doc = $item['doc'];
            $pdf->paginar = $item['paginar'];

            $pageCount = $pdf->setSourceFile(PdfParserStreamReader::createByString($doc));

            // Si es el contenido, contar total de páginas
            if ($pdf->paginar) {
                $pdf->totalPages = $pageCount;
                $pdf->pageNumber = 0; // reiniciar numeración
            }

            for ($page = 1; $page <= $pageCount; $page++) {
                $templateId = $pdf->importPage($page);
                $size = $pdf->getTemplateSize($templateId);

                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId, 0, 0, $size['width'], $size['height']);

                // Incrementar numeración solo para el contenido
                if ($pdf->paginar) {
                    $pdf->pageNumber++;
                }
            }
        }

        $filename = 'Informe_' . $this->reports->id . '.pdf';

        return response($pdf->Output($filename, 'S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
}
