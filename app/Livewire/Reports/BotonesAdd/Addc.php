<?php

namespace App\Livewire\Reports\BotonesAdd;

use Livewire\Component;
use App\Models\Report;
use App\Models\Content;
use App\Models\ReportTitle;
use App\Models\ReportTitleSubtitle;
use App\Models\ReportTitleSubtitleSection;
use Livewire\WithFileUploads;

class Addc extends Component
{
    use WithFileUploads;
    public $RTitle;
    public $RSubtitle;
    public $RSection;
    public $boton;
    public $contenido;
    public $report;
    public $rp;
    public $img1;
    public $img2;
    public $img3;
    public $leyenda1;
    public $leyenda2;
    public $leyenda3;
    public $path;
    public $path2;
    public $path3;

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

        $this->validate([
            'img1' => 'nullable|image|required_with:leyenda1',
            'img2' => 'nullable|image|required_with:leyenda2',
            'img3' => 'nullable|image|required_with:leyenda3',
            'leyenda1' => 'nullable|string|required_with:img1',
            'leyenda1' => 'nullable|string|required_with:img2',
            'leyenda1' => 'nullable|string|required_with:img3',
        ], [
            'img1.required_with'     => '⚠️ Si agregas una leyenda, también debes subir una imagen.',
            'leyenda1.required_with' => '⚠️ Si subes una imagen, también debes escribir una leyenda.',
            'img2.required_with'     => '⚠️ Si agregas una leyenda, también debes subir una imagen.',
            'leyenda2.required_with' => '⚠️ Si subes una imagen, también debes escribir una leyenda.',
            'img3.required_with'     => '⚠️ Si agregas una leyenda, también debes subir una imagen.',
            'leyenda3.required_with' => '⚠️ Si subes una imagen, también debes escribir una leyenda.',
        
        ]);
            $path = $this->img1 ? $this->img1->store('img_cont1', 'public') : null;
            $path2 = $this->img2 ? $this->img2->store('img_cont2', 'public') : null;
            $path3 = $this->img3 ? $this->img3->store('img_cont3', 'public') : null;

        if($boton == 'tit'){
            Content::create([
            'cont' => $this->contenido,
            'r_t_id'=> $id_,
            'img1'=>$path,
            'img2'=>$path2,
            'img3'=>$path3,
            'leyenda1'=> $this->leyenda1,
            'leyenda2'=> $this->leyenda2,
            'leyenda3'=> $this->leyenda3,

        ]);
        session()->flash('cont', 'Se agrego contenido de Titulo con exito.');
        $this->redirectRoute('my_reports.addcontenido',['id' => $id], navigate:true);

        } elseif($boton == 'sub'){
            Content::create([   
            'cont' => $this->contenido,
            'r_t_s_id'=> $id_,
            'img1'=>$path,
            'img2'=>$path2,
            'img3'=>$path3,
            'leyenda1'=> $this->leyenda1,
            'leyenda2'=> $this->leyenda2,
            'leyenda3'=> $this->leyenda3,

            ]);
        session()->flash('cont', 'Se agrego contenido de Subtitulo con exito.');
        $this->redirectRoute('my_reports.addcontenido',['id' => $id], navigate:true);

        } else if($boton == 'sec'){
            Content::create([
            'cont' => $this->contenido,
            'r_t_s_s_id'=> $id_,
            'img1'=>$path,
            'img2'=>$path2,
            'img3'=>$path3,
            'leyenda1'=> $this->leyenda1,
            'leyenda2'=> $this->leyenda2,
            'leyenda3'=> $this->leyenda3,
            ]);
        session()->flash('cont', 'Se agrego contenido de Sección con exito.');
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
