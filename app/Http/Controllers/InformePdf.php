<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use setasign\Fpdi\PdfParser\StreamReader as PdfParserStreamReader;
use setasign\Fpdi\Tcpdf\Fpdi;
use setasign\Fpdi\PdfReader\StreamReader;

class InformePdf extends Controller
{
    public function generar($id)
    {
        $reports = Report::findOrFail($id);

        // Generar PDFs separados (cada uno con su propio body)
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

        // Crear documento final con TCPDF + FPDI
        $pdf = new Fpdi();
        $pdf->SetCreator('Laravel');
        $pdf->SetAuthor('SSP');
        $pdf->SetTitle('Informe '.$reports->id);
        $pdf->SetMargins(15, 15, 15);

        $pageNumber = 1;

        foreach ([$pdfPortada, $pdfColaboradores, $pdfIndice, $pdfContenido] as $fileContent) {
            $pageCount = $pdf->setSourceFile(PdfParserStreamReader::createByString($fileContent));
            for ($page = 1; $page <= $pageCount; $page++) {
                $templateId = $pdf->importPage($page);
                $size = $pdf->getTemplateSize($templateId);
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId);

                // Numeración continua
                $pdf->SetFont('helvetica', '', 10);
                $pdf->SetY(-15);
                $pdf->Cell(0, 10, "Página ".$pageNumber, 0, 0, 'C');
                $pageNumber++;
            }
        }

        $filename = 'Informe_'.$reports->id.'.pdf';

        return response($pdf->Output($filename, 'S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="'.$filename.'"');
    }
}
