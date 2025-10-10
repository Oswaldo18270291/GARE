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
        public $clasificacion;
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
        $report->clasificacion    = $this->clasificacion;
        $report->logo             = $path;
        $report->img              = $path2; // aquí va la ruta, no el archivo
        $report->user_id          = Auth::id();
        $report->save();
        
        // Guardar TODOS los títulos
        $pivotTitles = [];
        $titles = \App\Models\Title::all(); 
        foreach ($titles as $title) {
            $pivot = ReportTitle::create([
                'report_id' => $report->id,
                'title_id'  => $title->id,
                'status'    => in_array($title->id, $this->title), // true si fue seleccionado
            ]);
            $pivotTitles[$title->id] = $pivot->id;
        }

        // Guardar TODOS los subtítulos
        $pivotSubtitles = [];
        $subtitles = \App\Models\Subtitle::all();
        foreach ($subtitles as $subtitle) {
            $titleId = $subtitle->title_id;

            if (isset($pivotTitles[$titleId])) {
                $pivot = ReportTitleSubtitle::create([
                    'r_t_id'      => $pivotTitles[$titleId], // id de report_titles
                    'subtitle_id' => $subtitle->id,
                    'status'      => in_array($subtitle->id, $this->subtitle),
                ]);
                $pivotSubtitles[$subtitle->id] = $pivot->id;
            }
        }

        // Guardar TODAS las secciones
        $sections = \App\Models\Section::all();
        foreach ($sections as $section) {
            $subtitleId = $section->subtitle_id;

            if (isset($pivotSubtitles[$subtitleId])) {
                ReportTitleSubtitleSection::create([
                    'r_t_s_id'   => $pivotSubtitles[$subtitleId], // id de report_title_subtitles
                    'section_id' => $section->id,
                    'status'     => in_array($section->id, $this->section),
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



    public $expandAll = false;

    public function toggleAll()
    {
        $allTitles = $this->titles->pluck('id')->toArray();
        $allSubtitles = $this->titles->flatMap->subtitles->pluck('id')->toArray();
        $allSections = $this->titles->flatMap->subtitles->flatMap->sections->pluck('id')->toArray();

        $alreadySelected =
            count($this->title) === count($allTitles) &&
            count($this->subtitle) === count($allSubtitles) &&
            count($this->section) === count($allSections);

        if ($alreadySelected) {
            // Si ya estaban todos seleccionados → limpiar
            $this->title = [];
            $this->subtitle = [];
            $this->section = [];
            $this->expandAll = false;
        } else {
            // Seleccionar todos
            $this->title = $allTitles;
            $this->subtitle = $allSubtitles;
            $this->section = $allSections;
            $this->expandAll = true;
        }
    }

    public function render()
    {
        return view('livewire.c-reports.index');
    }
}
