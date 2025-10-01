<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\Report;
use App\Models\ReportTitle;
use App\Models\ReportTitleSubtitle;
use App\Models\ReportTitleSubtitleSection;
use Illuminate\Support\Facades\Storage;
use App\Models\Content;
class Addcontenido extends Component
{
    public $report;
    public $content;


    public function mount($id)
    {
        $rep= Report::findOrFail($id);

        $this->authorize('update', $rep); // 👈 ahora sí se evalúa la policy
        // Cargar el reporte principal
        $this->report = Report::findOrFail($id);

        // Cargar títulos relacionados
        $this->report->titles = ReportTitle::where('report_id', $this->report->id)->where('status',1)->orderBy('title_id', 'asc')->get();

        foreach ($this->report->titles as $title) {
            // Cargar subtítulos de cada título
            $title->subtitles = ReportTitleSubtitle::where('r_t_id', $title->id)->where('status',1)->orderBy('subtitle_id', 'asc')->get();

            foreach ($title->subtitles as $subtitle) {
                // Cargar secciones de cada subtítulo
                $subtitle->sections = ReportTitleSubtitleSection::where('r_t_s_id', $subtitle->id)->where('status',1)->orderBy('section_id', 'asc')->get();
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

    public function deleteContent($id,$boton,$rp){
        
        if($boton == 'tit'){
            $content = Content::where('r_t_id', $id)->first();

            foreach (['img1', 'img2', 'img3'] as $imgField) {
                if ($content->$imgField && Storage::disk('public')->exists($content->$imgField)) {
                    Storage::disk('public')->delete($content->$imgField);
                }
            }
            $content->delete();
            session()->flash('eliminar', 'El contenido se eliminó correctamente.');
            $this->redirectRoute('my_reports.addcontenido',['id' => $rp], navigate:true);

        } elseif($boton == 'sub'){
             $content = Content::where('r_t_s_id', $id)->first();
            foreach (['img1', 'img2', 'img3'] as $imgField) {
                if ($content->$imgField && Storage::disk('public')->exists($content->$imgField)) {
                    Storage::disk('public')->delete($content->$imgField);
                }
            }
            $content->delete();
            session()->flash('eliminar', 'El contenido se eliminó correctamente.');
            $this->redirectRoute('my_reports.addcontenido',['id' => $rp], navigate:true);
        } else if($boton == 'sec'){
            $content = Content::where('r_t_s_s_id', $id)->first();
        foreach (['img1', 'img2', 'img3'] as $imgField) {
            if ($content->$imgField && Storage::disk('public')->exists($content->$imgField)) {
                Storage::disk('public')->delete($content->$imgField);
            }
        }
        $content->delete();
        session()->flash('eliminar', 'El contenido se eliminó correctamente.');
        $this->redirectRoute('my_reports.addcontenido',['id' => $rp], navigate:true);

        }
       
   
    }

    public function render()
    {
        return view('livewire.reports.addcontenido', [
            'report' => $this->report,
        ]);
    }
}
