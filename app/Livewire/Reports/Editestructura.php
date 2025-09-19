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
    public $titles = [];
    public $subtitles = [];
    public $sections = [];

    public function mount($id)
    {   
        $titles = $this->titles ?? [];       // si es null lo pone como []
        $subtitles = $this->subtitles ?? [];
        $sections = $this->sections ?? [];
        $this->report = Report::findOrFail($id);
        $img = Report::select('img')->findOrFail($id);
        $this->nombre_empresa = $this->report->nombre_empresa;
        $this->giro_empresa   = $this->report->giro_empresa;
        $this->ubicacion      = $this->report->ubicacion;
        $this->telefono       = $this->report->telefono;
        $this->representante  = $this->report->representante;
        $this->fecha_analisis = $this->report->fecha_analisis;
        $this->colaborador    = $this->report->colaborador1;

        $this->report->titles = ReportTitle::where('report_id', $this->report->id)->get();
        foreach ($this->report->titles as $title) {
            $title->subtitles = ReportTitleSubtitle::where('r_t_id', $title->id)->get();
            foreach ($title->subtitles as $subtitle) {
                $subtitle->sections = ReportTitleSubtitleSection::where('r_t_s_id', $subtitle->id)->get();
            }
        }

        // Precargar selecciones
        $this->titles = $this->report->titles->where('status', true)->pluck('id')->toArray();

        foreach ($this->report->titles as $title) {
            foreach ($title->subtitles as $subtitle) {
                if ($subtitle->status) {
                    $this->subtitles[] = $subtitle->id;
                }

                foreach ($subtitle->sections as $section) {
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
        $this->report->save();
        foreach ($this->report->titles as $title) {
        $title->status = in_array($title->id, $this->titles);
        $title->save();

        foreach ($title->subtitles as $subtitle) {
            $subtitle->status = in_array($subtitle->id, $this->subtitles);
            $subtitle->save();

            foreach ($subtitle->sections as $section) {
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
        ]);
    }
}
