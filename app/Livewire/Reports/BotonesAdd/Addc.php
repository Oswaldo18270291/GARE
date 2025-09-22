<?php

namespace App\Livewire\Reports\BotonesAdd;

use Livewire\Component;
use App\Models\Report;
use App\Models\Content;
use App\Models\ReportTitle;
use App\Models\ReportTitleSubtitle;
use App\Models\ReportTitleSubtitleSection;

class Addc extends Component
{
    public $RTitle;
    public $RSubtitle;
    public $RSection;
    public $boton;
    public $contenido;
    public $report;
    public $rp;
    
    
    public function mount($id, $boton, $rp)
    {
        $rp = Report::findOrFail($rp);
        if($boton == 'tit'){
            $this->RTitle = ReportTitle::findOrFail($id);
            $this->RSubtitle = null;
        } elseif($boton == 'sub'){
            $this->RSubtitle = ReportTitleSubtitle::findOrFail($id);
            $this->RTitle = null;
        } else if($boton == 'sec'){
            $this->RTitle = null;
            $this->RSubtitle = null;
            $this->RSection = ReportTitleSubtitleSection::findOrFail($id);

        }

    }

    public function store($id_,$boton,$id){
     
        if($boton == 'tit'){
            Content::create([
            'cont' => $this->contenido,
            'r_t_id'=> $id_,
        ]);
        session()->flash('cont', 'Se agrego contenido de Titulo con exito.');
        $this->redirectRoute('my_reports.addcontenido',['id' => $id], navigate:true);

        } elseif($boton == 'sub'){
            Content::create([   
            'cont' => $this->contenido,
            'r_t_s_id'=> $id_,

            ]);
        session()->flash('cont', 'Se agrego contenido de Subtitulo con exito.');
        $this->redirectRoute('my_reports.addcontenido',['id' => $id], navigate:true);

        } else if($boton == 'sec'){
            Content::create([
            'cont' => $this->contenido,
            'r_t_s_s_id'=> $id_,
            
            ]);
        session()->flash('cont', 'Se agrego contenido de Seccioón con exito.');
        $this->redirectRoute('my_reports.addcontenido',['id' => $id], navigate:true);

        }
    }
    public function render()
    {
        return view('livewire.reports.botones-add.addc', [
            'RTitle' => $this->RTitle,
            'RSubtitle' => $this->RSubtitle,
            'RSection' => $this->RSection,
            'boton'  => $this->boton,
            'rp'  => $this->rp,
        ]);

    }
}
