<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\Report;
use App\Models\ReportTitle;
use App\Models\ReportTitleSubtitle;
use App\Models\ReportTitleSubtitleSection;
use Livewire\WithFileUploads;
class Editestructura extends Component
{
    use WithFileUploads;
    public $report;
    public $logo;
    public $img;
    /*public $report_id;
    public $titles;
    public $nombre_empresa;
    public $giro_empresa;
    public $ubicacion;
    public $telefono;
    public $representante;
    public $fecha_analisis;
    public $colaborador;
    public $title = [];
    public $subtitle = [];
    public $section = []; */   

    public function mount($id)
    {
        
        // Cargar el reporte principal
        $this->report = Report::findOrFail($id);
        $img = Report::select('img')->findOrFail($id);
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

    public function update()
    {
        $this->validate([
            'logo' => 'required|image|max:5120', // máximo 5MB
            'img' => 'required|image|max:5120', // máximo 5MB
        ]);
    }
    public function render()
    {
            return view('livewire.reports.editestructura', [
            'report' => $this->report,
            'img' => $this->img,
        ]);
    }
}
