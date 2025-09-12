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
use Livewire\WithFileUploads;

class Index extends Component
{   
        use WithFileUploads;
        public $report_id;
        public $titles;
        public $nombre_empresa;
        public $giro_empresa;
        public $ubicacion;
        public $telefono;
        public $representante;
        public $fecha_analisis;
        public $colaborador;
        public $logo;
        public $img;
        public $title = [];
        public $subtitle = [];
        public $section = [];

    public function store()
    {
        $this->validate([
            'logo' => 'required|image|max:5120', // máximo 5MB
            'img' => 'required|image|max:5120', // máximo 5MB
        ]);

        // Guardar en storage/app/public/logos
        $path = $this->logo->store('logos', 'public');
        $path2 = $this->img->store('img_p', 'public');

        // Crear el reporte
        $report = new \App\Models\Report();
        $report->nombre_empresa   = $this->nombre_empresa;
        $report->giro_empresa     = $this->giro_empresa;
        $report->ubicacion        = $this->ubicacion;
        $report->telefono         = $this->telefono;
        $report->representante    = $this->representante;
        $report->fecha_analisis   = $this->fecha_analisis;
        $report->colaborador1     = $this->colaborador;
        $report->logo             = $path;
        $report->img              = $path2; // aquí va la ruta, no el archivo
        $report->user_id          = Auth::id();
        $report->save();

    // Guardar Títulos seleccionados
    $pivotTitles = []; // array para mapear title_id → report_titles.id
    foreach ($this->title as $titleId) {
        $pivot = ReportTitle::create([
            'report_id' => $report->id,
            'title_id'  => $titleId,
            'status'    => true,
        ]);

        $pivotTitles[$titleId] = $pivot->id;
    }

    // Guardar Subtítulos seleccionados
    $pivotSubtitles = []; // mapear subtitle_id → report_title_subtitles.id
    foreach ($this->subtitle as $subtitleId) {
        // Buscamos a qué título pertenece este subtítulo
        $subtitle = \App\Models\Subtitle::find($subtitleId);
        $titleId  = $subtitle->title_id;

        if (isset($pivotTitles[$titleId])) {
            $pivot = ReportTitleSubtitle::create([
                'r_t_id'      => $pivotTitles[$titleId], // id de report_titles
                'subtitle_id' => $subtitleId,
                'status'      => true,
            ]);

            $pivotSubtitles[$subtitleId] = $pivot->id;
        }
    }

    // Guardar Secciones seleccionadas
    foreach ($this->section as $sectionId) {
        // Buscamos a qué subtítulo pertenece esta sección
        $section   = \App\Models\Section::find($sectionId);
        $subtitleId = $section->subtitle_id;

        if (isset($pivotSubtitles[$subtitleId])) {
            ReportTitleSubtitleSection::create([
                'r_t_s_id'  => $pivotSubtitles[$subtitleId], // id de report_title_subtitles
                'section_id'=> $sectionId,
                'status'    => true,
            ]);
        }
    }

        session()->flash('success', 'Se creó el informe de "' . $this->nombre_empresa . '" con éxito.');

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
