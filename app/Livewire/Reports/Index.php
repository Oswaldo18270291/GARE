<?php

namespace App\Livewire\Reports;

use App\Http\Controllers\InformePdf;
use Illuminate\Support\Facades\Auth;

use Livewire\WithPagination;
use App\Models\Report;
use App\Models\ReportTitle;
use App\Models\ReportTitleSubtitle;
use App\Models\ReportTitleSubtitleSection;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
class Index extends Component
{   
    use WithPagination;


    public function status($id)
    {
        $report = Report::findOrFail($id);

        // 1️⃣ Generar el PDF con tu método existente
        $pdfController = new InformePdf();
        $pdf = $pdfController->generar($id);

        // 2️⃣ Guardar el archivo físico
        $nombre = "Informe_{$report->nombre_empresa}.pdf";
        $rutaRelativa = "reports/{$nombre}";
        Storage::disk('public')->put($rutaRelativa, $pdf->getOriginalContent());

        // 3️⃣ Actualizar el estado del reporte
        $report->update([
            'pdf_path' => $rutaRelativa,
            'status' => true,
        ]);
        session()->flash('success', 'El informe se ha finalizado');
        $this->redirectRoute('history.index', navigate:true);
    }

public function delete($id)
{
    $report = Report::findOrFail($id);

    // 🔹 Eliminar imágenes principales del reporte
    foreach (['logo', 'img'] as $field) {
        if ($report->$field && Storage::disk('public')->exists($report->$field)) {
            Storage::disk('public')->delete($report->$field);
        }
    }

    // 🔹 Eliminar títulos
    $reportTitles = ReportTitle::where('report_id', $report->id)->get();

    foreach ($reportTitles as $title) {

        // Contenidos ligados directamente al título
        foreach ($title->contents as $content) {
            $this->deleteContentWithImages($content);
        }

        // Subtítulos asociados
        $subtitles = ReportTitleSubtitle::where('r_t_id', $title->id)->get();
        foreach ($subtitles as $subtitle) {

            // Contenidos ligados al subtítulo
            foreach ($subtitle->contents as $content) {
                $this->deleteContentWithImages($content);
            }

            // Secciones asociadas
            $sections = ReportTitleSubtitleSection::where('r_t_s_id', $subtitle->id)->get();
            foreach ($sections as $section) {

                // Contenidos ligados a la sección
                foreach ($section->contents as $content) {
                    $this->deleteContentWithImages($content);
                }

                $section->delete();
            }

            $subtitle->delete();
        }

        $title->delete();
    }

    // 🔹 Finalmente eliminar el reporte
    $report->delete();
}

/**
 * Helper para eliminar imágenes y contenido
 */
protected function deleteContentWithImages($content)
{
    foreach (['img1', 'img2', 'img3'] as $imgField) {
        if ($content->$imgField && Storage::disk('public')->exists($content->$imgField)) {
            Storage::disk('public')->delete($content->$imgField);
        }
    }
    $content->delete();
    session()->flash('eliminar', 'El informe y sus relaciones se eliminaron correctamente.');

}


    public function render()
    {
        return view('livewire.reports.index', [
            'reports' => Report::where('status', false)
                ->where('user_id', Auth::id()) // 👈 solo del usuario autenticado
                ->paginate(10),
        ]);
    }


    public function addcontent ($id){
        $this->redirectRoute('my_reports.addcontenido',['id' => $id] ,navigate:true);
    }
        public function editeditstructure ($id){
        $this->redirectRoute('my_reports.editestructura',['id' => $id] ,navigate:true);
    }
}
