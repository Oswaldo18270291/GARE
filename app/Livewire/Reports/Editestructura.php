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
        $this->report->titles = ReportTitle::where('report_id', $this->report->id)
                                ->whereHas('title', function ($q) {
                                    $q->where('status', 1);
                                })
                                ->get();
        foreach ($this->report->titles as $title) {
            $title->subtitles = ReportTitleSubtitle::where('r_t_id', $title->id)
                                ->whereHas('subtitle', function ($q) {
                                    $q->where('status', 1);
                                })
                                ->get();
            foreach ($title->subtitles as $subtitle) {
                $subtitle->sections = ReportTitleSubtitleSection::where('r_t_s_id', $subtitle->id)
                                ->whereHas('section', function ($q) {
                                    $q->where('status', 1);
                                })
                                ->get();
            }
        }

        $this->titles = $this->report->reportTitles->where('status', true)->pluck('id')->toArray();

        foreach ($this->report->reportTitles as $title) {
            foreach ($title->reportTitleSubtitles as $subtitle) {
                if ($subtitle->status) {
                    $this->subtitles[] = $subtitle->id;
                }

                foreach ($subtitle->reportTitleSubtitleSections as $section) {
                    if ($section->status) {
                        $this->sections[] = $section->id;
                    }
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
        foreach ($this->report->reportTitles as $title) {
            $title->status = in_array($title->id, $this->titles);
            $title->save();

            foreach ($title->reportTitleSubtitles as $subtitle) {
                $subtitle->status = in_array($subtitle->id, $this->subtitles);
                $subtitle->save();

                foreach ($subtitle->reportTitleSubtitleSections as $section) {
                    $section->status = in_array($section->id, $this->sections);
                    $section->save();
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
        ]);
    }
}
