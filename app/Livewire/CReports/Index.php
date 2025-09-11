<?php

namespace App\Livewire\CReports;

use App\Models\ReportTitle;
use App\Models\ReportTitleSubtitle;
use App\Models\ReportTitleSubtitleSection;
use App\Models\Title;
use App\Model\Report;
use App\Models\Report as ModelsReport;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
        public $titles;
        public $nombre_empresa;
        public $giro_empresa;
        public $ubicacion;
        public $telefono;
        public $representante;
        public $fecha_analisis;
        public $colaborador;
        public $logo;
        public $title=[];
        public $subtitle=[];
        public $section=[];

    public function store()
    {
        $report =  new ModelsReport();
        $report->nombre_empresa = $this->nombre_empresa;
        $report->giro_empresa = $this->giro_empresa;
        $report->ubicacion = $this->ubicacion;
        $report->telefono = $this->telefono;
        $report->representante = $this->representante;
        $report->fecha_analisis = $this->fecha_analisis;
        $report->colaborador1 = $this->colaborador;
        $report->logo = $this->logo;
        $report->user_id = Auth::user()->id;
        $report->save();
        session()->flash('success','Se creó el informe de  "' . $this->nombre_empresa. '" con éxito.');
        $this->redirectRoute('my_reports.index', navigate:true);
    }
    public function mount()
    {
        $this->titles = Title::with('subtitles.sections')->get();
    }


    public function render()
    {
        return view('livewire.c-reports.index');
    }
}
