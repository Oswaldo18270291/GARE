<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\Report;
use App\Models\ReportTitle;
use App\Models\ReportTitleSubtitle;
use App\Models\ReportTitleSubtitleSection;
use Illuminate\Support\Facades\Storage;
use App\Models\Content;
class Addcontenido extends Component
{
    public $report;


    public function mount($id)
    {
        // Cargar el reporte principal
        $this->report = Report::findOrFail($id);

        // Cargar títulos relacionados
        $this->report->titles = ReportTitle::where('report_id', $this->report->id)->where('status',1)->get();

        foreach ($this->report->titles as $title) {
            // Cargar subtítulos de cada título
            $title->subtitles = ReportTitleSubtitle::where('r_t_id', $title->id)->where('status',1)->get();

            foreach ($title->subtitles as $subtitle) {
                // Cargar secciones de cada subtítulo
                $subtitle->sections = ReportTitleSubtitleSection::where('r_t_s_id', $subtitle->id)->where('status',1)->get();
            }
        }
    }

    public function Addc($id, $boton,$rp)
    {
        $this->redirectRoute('my_reports.addcontenido.Addc', [
            'id' => $id, 
            'boton' => $boton,
            'rp' => $rp,
        ], navigate: true);
    }

    public function Editc($id, $boton,$rp)
    {
        $this->redirectRoute('my_reports.addcontenido.Editc', [
            'id' => $id, 
            'boton' => $boton,
            'rp' => $rp,
        ], navigate: true);
    }

    public function deleteContent($id,$report){
        $content = Content::findOrFail($id);
        foreach (['img1', 'img2', 'img3'] as $imgField) {
            if ($content->$imgField && Storage::disk('public')->exists($content->$imgField)) {
                Storage::disk('public')->delete($content->$imgField);
            }
        }
        $content->delete();
        session()->flash('eliminar', 'El contenido se eliminó correctamente.');
        $this->redirectRoute('my_reports.addcontenido',['id' => $report], navigate:true);
   
    }

    public function render()
    {
        return view('livewire.reports.addcontenido', [
            'report' => $this->report,
        ]);
    }
}
