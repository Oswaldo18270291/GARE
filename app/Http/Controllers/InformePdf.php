<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use setasign\Fpdi\PdfParser\StreamReader as PdfParserStreamReader;
use setasign\Fpdi\Tcpdf\Fpdi;

class InformePdf extends Controller
{
    public function generar($id)
    {
        $reports = Report::findOrFail($id);

        // Generar PDFs separados
        $pdfPortada = Pdf::loadView('plantillas.portada', [
            'reports' => $reports,
        ])->output();

        $pdfColaboradores = Pdf::loadView('plantillas.colaboradores', [
            'reports' => $reports,
        ])->output();

        $pdfIndice = Pdf::loadView('plantillas.indice', [
            'reports' => $reports,
        ])->output();

        $pdfContenido = Pdf::loadView('plantillas.contenido', [
            'reports' => $reports,
        ])->output();

        // Clase personalizada para pies de página
        $pdf = new class extends Fpdi {
            public $pageNumber = 1;

            public function Footer()
            {
                if ($this->pageNumber > 1) {
                    $this->SetY(-15);
                    $this->SetFont('helvetica', '', 10);
                    $this->Cell(0, 10, 'Página ' . $this->pageNumber, 0, 0, 'C');
                }
                $this->pageNumber++;
            }
        };

        $pdf->SetCreator('Laravel');
        $pdf->SetAuthor('SSP');
        $pdf->SetTitle('Informe ' . $reports->id);
        $pdf->SetMargins(15, 15, 15);
        $pdf->setPrintHeader(false); // No usamos Header
        $pdf->setPrintFooter(true);

        $allDocs = [$pdfPortada, $pdfColaboradores, $pdfIndice, $pdfContenido];

        foreach ($allDocs as $doc) {
            $pageCount = $pdf->setSourceFile(PdfParserStreamReader::createByString($doc));
            for ($page = 1; $page <= $pageCount; $page++) {
                $templateId = $pdf->importPage($page);
                $size = $pdf->getTemplateSize($templateId);

                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId, 0, 0, $size['width'], $size['height']);

                // 🔑 Poner número en encabezado SOLO en la primera página
                if ($pdf->pageNumber === 1) {
                    $pdf->SetY(10);
                    $pdf->SetFont('helvetica', '', 10);
                    $pdf->Cell(0, 10, 'Página 1', 0, 0, 'C');
                }
            }
        }

        $filename = 'Informe_' . $reports->id . '.pdf';

        return response($pdf->Output($filename, 'S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
}
