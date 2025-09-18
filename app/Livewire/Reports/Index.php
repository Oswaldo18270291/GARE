<?php

namespace App\Livewire\Reports;

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
        $report->status = true;
        $report->save();
        session()->flash('success', 'El reporte se ha finalizado');
        $this->redirectRoute('history.index', navigate:true);
    }

    public function delete($id)
    {
        $report = Report::findOrFail($id);

        // 🔹 Eliminar títulos asociados
        if ($report->logo && Storage::disk('public')->exists($report->logo)) {
        Storage::disk('public')->delete($report->logo);
        }
        if ($report->logo && Storage::disk('public')->exists($report->img)) {
        Storage::disk('public')->delete($report->img);
        }
        $reportTitles = ReportTitle::where('report_id', $report->id)->get();

        foreach ($reportTitles as $title) {
            // Eliminar subtítulos asociados
            $subtitles = ReportTitleSubtitle::where('r_t_id', $title->id)->get();

            foreach ($subtitles as $subtitle) {
                // Eliminar secciones asociadas
                ReportTitleSubtitleSection::where('r_t_s_id', $subtitle->id)->delete();
            }

            // Eliminar subtítulos después de limpiar secciones
            ReportTitleSubtitle::where('r_t_id', $title->id)->delete();
        }

        // Eliminar títulos después de limpiar subtítulos
        ReportTitle::where('report_id', $report->id)->delete();

        // Finalmente eliminar el reporte
        $report->delete();

        // Mensaje flash
        session()->flash('eliminar', 'El reporte y sus relaciones se eliminaron correctamente.');
    }

    public function render()
    {
        return view('livewire.reports.index', [
            'reports' => Report::where('status', false)->paginate(10),
        ]);
    }

    public function addcontent ($id){
        $this->redirectRoute('my_reports.addcontenido',['id' => $id] ,navigate:true);
    }
        public function editeditstructure ($id){
        $this->redirectRoute('my_reports.editestructura',['id' => $id] ,navigate:true);
    }
}
