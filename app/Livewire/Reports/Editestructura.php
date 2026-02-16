<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\Report;
use App\Models\Title;
use App\Models\ReportTitle;
use App\Models\ReportTitleSubtitle;
use App\Models\ReportTitleSubtitleSection;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Editestructura extends Component
{
    use WithFileUploads;
    public $report;
    public $logo;
    public $img;
    public $report_id;
    public $title;
    public $nombre_empresa;
    public $giro_empresa;
    public $ubicacion;
    public $telefono;
    public $representante;
    public $fecha_analisis;
    public $colaborador;
    public $clasificacion;
    public $titles = [];
    public $subtitles = [];
    public $sections = [];

    public $titlesCatalog;

    public $img_portada;        // Portada seleccionada (string)
    public $portada_custom; 

    public function mount($id)
    {   
        $report = Report::findOrFail($id);
        $this->authorize('update', $report); // 👈 ahora sí se evalúa la policy
        $titles = $this->titles ?? [];       // si es null lo pone como []
        $subtitles = $this->subtitles ?? [];
        $sections = $this->sections ?? [];
        $this->report = Report::with([
            'reportTitles.title',
            'reportTitles.reportTitleSubtitles.subtitle',
            'reportTitles.reportTitleSubtitles.reportTitleSubtitleSections.section'
        ])->findOrFail($id);
        $img = Report::select('img')->findOrFail($id);
        $logo = Report::select('logo')->findOrFail($id);
        $this->nombre_empresa = $this->report->nombre_empresa;
        $this->giro_empresa   = $this->report->giro_empresa;
        $this->ubicacion      = $this->report->ubicacion;
        $this->telefono       = $this->report->telefono;
        $this->representante  = $this->report->representante;
        $this->fecha_analisis = $this->report->fecha_analisis;
        $this->colaborador    = $this->report->colaborador1;
        $this->clasificacion   = $this->report->clasificacion;
        $this->img_portada = $this->report->img_portada;
      // 1) Catálogo ACTIVO
        $this->titlesCatalog = Title::query()
            ->where('status', 1)
            ->with([
                'subtitles' => fn ($q) => $q->where('status', 1)->orderBy('orden')
                    ->with(['sections' => fn ($qq) => $qq->where('status', 1)->orderBy('orden')]),
            ])
            ->orderBy('orden')
            ->get();

        // 2) Pivots del reporte (cargados de una vez)
        $reportTitles = ReportTitle::where('report_id', $this->report->id)->get()->keyBy('title_id');

        $reportTitleSubtitles = ReportTitleSubtitle::whereIn(
                'r_t_id',
                $reportTitles->pluck('id')->all()
            )->get();
        $subtitlesBySubtitleId = $reportTitleSubtitles->keyBy('subtitle_id');


        // Ojo: sections pivot depende del r_t_s_id
        $reportTitleSubtitleSections = ReportTitleSubtitleSection::whereIn(
                'r_t_s_id',
                $reportTitleSubtitles->pluck('id')->all()
            )->get()->keyBy('section_id');

        // 3) Preselecciones (con IDs REALES del catálogo)
        $this->titles = [];
        $this->subtitles = [];
        $this->sections = [];

        foreach ($this->titlesCatalog as $t) {
            $rt = $reportTitles->get($t->id);
            if ($rt?->status)     $this->titles[]    = $t->id;

            foreach ($t->subtitles as $st) {
                $rts = $subtitlesBySubtitleId->get($st->id);
                if ($rts?->status)    $this->subtitles[] = $st->id;
                foreach ($st->sections as $sec) {
                    $rtssec = $reportTitleSubtitleSections->get($sec->id);
                    if ($rtssec?->status) $this->sections[]  = $sec->id;
                }
            }
        }


    }

    public function update()
    {
        $this->validate([
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // máximo 5MB
            'img' => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // máximo 5MB
        ]);

        $this->report->update([
        'nombre_empresa' => $this->nombre_empresa,
        'giro_empresa'   => $this->giro_empresa,
        'ubicacion'      => $this->ubicacion,
        'telefono'       => $this->telefono,
        'representante'  => $this->representante,
        'fecha_analisis' => $this->fecha_analisis,
        'colaborador1'   => $this->colaborador,
        'clasificacion'  => $this->clasificacion,
        ]);

        if ($this->logo) {
            // Borrar logo anterior si existe
            if ($this->report->logo && Storage::disk('public')->exists($this->report->logo)) {
                Storage::disk('public')->delete($this->report->logo);
            }

            // Guardar nuevo logo
            $path = $this->logo->store('logos', 'public');
            $this->report->logo = $path;
        }


        if ($this->img) {
        // Borrar logo anterior si existe
            if ($this->report->img && Storage::disk('public')->exists($this->report->img)) {
                Storage::disk('public')->delete($this->report->img);
            }

            // Guardar nuevo logo
            $path = $this->img->store('img', 'public');
            $this->report->img = $path;
        }
        $this->report->update([
            'img_portada' => $this->img_portada,
        ]);
        $this->report->save();
        // TITLES
        foreach ($this->titlesCatalog as $t) {
            $rt = ReportTitle::firstOrCreate(
                ['report_id' => $this->report->id, 'title_id' => $t->id],
                ['status' => false]
            );

            $rt->update(['status' => in_array($t->id, $this->titles ?? [])]);

            // SUBTITLES
            foreach ($t->subtitles as $st) {
                $rts = ReportTitleSubtitle::firstOrCreate(
                    ['r_t_id' => $rt->id, 'subtitle_id' => $st->id],
                    ['status' => false]
                );

                $rts->update(['status' => in_array($st->id, $this->subtitles ?? [])]);

                // SECTIONS
                foreach ($st->sections as $sec) {
                    $rtssec = ReportTitleSubtitleSection::firstOrCreate(
                        ['r_t_s_id' => $rts->id, 'section_id' => $sec->id],
                        ['status' => false]
                    );

                    $rtssec->update(['status' => in_array($sec->id, $this->sections ?? [])]);
                }
            }
        }

        session()->flash('up', 'Informe actualizado correctamente ✅');
        $this->redirectRoute('my_reports.index', navigate:true);

    }
    
    public function render()
    {
            return view('livewire.reports.editestructura', [
            'report' => $this->report,
            'img' => $this->img,
            'logo' => $this->logo,
            'titlesCatalog' => $this->titlesCatalog,
        ]);
    }
}
