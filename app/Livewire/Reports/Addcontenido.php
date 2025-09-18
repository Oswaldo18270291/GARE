<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\Report;
use App\Models\ReportTitle;
use App\Models\ReportTitleSubtitle;
use App\Models\ReportTitleSubtitleSection;

class Addcontenido extends Component
{
    public $report;


    public function mount($id)
    {
        // Cargar el reporte principal
        $this->report = Report::findOrFail($id);

        // Cargar títulos relacionados
        $this->report->titles = ReportTitle::where('report_id', $this->report->id)->get();

        foreach ($this->report->titles as $title) {
            // Cargar subtítulos de cada título
            $title->subtitles = ReportTitleSubtitle::where('r_t_id', $title->id)->get();

            foreach ($title->subtitles as $subtitle) {
                // Cargar secciones de cada subtítulo
                $subtitle->sections = ReportTitleSubtitleSection::where('r_t_s_id', $subtitle->id)->get();
            }
        }
    }

    public function Addc($id, $boton)
    {
        $this->redirectRoute('my_reports.addcontenido.Addc', [
            'id' => $id, 
            'boton' => $boton
        ], navigate: true);
    }


    public function render()
    {
        return view('livewire.reports.addcontenido', [
            'report' => $this->report,
        ]);
    }
}
