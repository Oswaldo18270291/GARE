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
        public $clasificacion='';
        public $title = [];
        public $subtitle = [];
        public $section = [];
        public $img_portada; // 🔹 Nueva propiedad para portada seleccionada o cargada
        public $portada_custom; // para archivo subido

    public function store()
    {
        $this->validate([
             'img_portada' => 'required',
            'logo' => 'required|image|max:5120',
            'img' => 'required|image|max:5120',
            'portada_custom' => 'nullable|image|max:5120', // 🔹 Validación opcional
       ] , [
            'img_portada.required' => 'Debes seleccionar una portada.',
        ]);

        // Guardar imágenes
        $pathLogo = $this->logo->store('logos', 'public');
        $pathImg = $this->img->store('img_p', 'public');

        // Si sube su propia portada, guardarla
        $portadaPath = null;
        if ($this->portada_custom) {
            $portadaPath = $this->portada_custom->store('img_portada_custom', 'public');
        } else {
            $portadaPath = $this->img_portada; // 🔹 Guardar nombre o ruta seleccionada
        }

        // Crear el reporte
        $report = new \App\Models\Report();
        $report->nombre_empresa = $this->nombre_empresa;
        $report->giro_empresa = $this->giro_empresa;
        $report->ubicacion = $this->ubicacion;
        $report->telefono = $this->telefono;
        $report->representante = $this->representante;
        $report->fecha_analisis = $this->fecha_analisis;
        $report->colaborador1 = $this->colaborador;
        $report->clasificacion = $this->clasificacion;
        $report->logo = $pathLogo;
        $report->img = $pathImg;
        $report->img_portada = $portadaPath; // 🔹 Guardamos la portada seleccionada o subida
        $report->user_id = Auth::id();
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
        $this->titles = Title::with([
            'subtitles' => function($query){
                $query->where('status', true)
                    ->with(['sections' => function($q){
                        $q->where('status', true);
                    }]);
            }
        ])
        ->where('status', true)
        ->get();

        // 🔹 PRESELECCIONAR TODO DESDE EL INICIO
        $this->title = $this->titles->pluck('id')->toArray();

        $this->subtitle = $this->titles
            ->flatMap->subtitles
            ->pluck('id')
            ->toArray();

        $this->section = $this->titles
            ->flatMap->subtitles
            ->flatMap->sections
            ->pluck('id')
            ->toArray();

        // 🔹 Expandir todo visualmente
        $this->expandAll = true;
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
