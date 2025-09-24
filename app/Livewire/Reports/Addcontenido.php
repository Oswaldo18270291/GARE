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


    public function render()
    {
        return view('livewire.reports.addcontenido', [
            'report' => $this->report,
        ]);
    }
}
