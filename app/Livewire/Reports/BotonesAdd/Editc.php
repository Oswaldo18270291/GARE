<?php

namespace App\Livewire\Reports\BotonesAdd;

use Livewire\Component;
use App\Models\Content;
use Livewire\WithFileUploads;
use App\Models\ReportTitle;
use App\Models\ReportTitleSubtitle;
use App\Models\ReportTitleSubtitleSection;
use App\Models\Report;

class Editc extends Component
{
    use WithFileUploads;
    public $RTitle;
    public $RSubtitle;
    public $RSection;
    public $content;
    public $contenido;
    public $img1;
    public $img2;
    public $img3;
    public $leyenda1;
    public $leyenda2;
    public $leyenda3;
    public $boton;
    public $rp;
    // Para previsualizar las imágenes antiguas
    public $oldImg1;
    public $oldImg2;
    public $oldImg3;

    public function mount($id,$boton,$rp)
    {
    $report = Report::findOrFail($rp);

    $this->authorize('update', $report); // 👈 ahora sí se evalúa la policy
        if($boton == 'tit'){
            $this->content = Content::where('r_t_id', $id)->first();
            // Cargamos valores existentes
            
            $this->contenido = $this->content->cont;
            $this->leyenda1 = $this->content->leyenda1;
            $this->leyenda2 = $this->content->leyenda2;
            $this->leyenda3 = $this->content->leyenda3;

            $this->oldImg1 = $this->content->img1;
            $this->oldImg2 = $this->content->img2;
            $this->oldImg3 = $this->content->img3;
            $this->RTitle = ReportTitle::findOrFail($id);
        } elseif($boton == 'sub'){
            $this->RTitle = null;
            $this->content = Content::where('r_t_s_id', $id)->first();
            // Cargamos valores existentes
           
            $this->contenido = $this->content->cont;
            $this->leyenda1 = $this->content->leyenda1;
            $this->leyenda2 = $this->content->leyenda2;
            $this->leyenda3 = $this->content->leyenda3;

            $this->oldImg1 = $this->content->img1;
            $this->oldImg2 = $this->content->img2;
            $this->oldImg3 = $this->content->img3;
            $this->RSubtitle = ReportTitleSubtitle::findOrFail($id);
        } else if($boton == 'sec'){
            $this->RTitle = null;
            $this->RSubtitle = null;
            $this->content = Content::where('r_t_s_s_id', $id)->first();
            // Cargamos valores existentes
            
            $this->contenido = $this->content->cont;
            $this->leyenda1 = $this->content->leyenda1;
            $this->leyenda2 = $this->content->leyenda2;
            $this->leyenda3 = $this->content->leyenda3;

            $this->oldImg1 = $this->content->img1;
            $this->oldImg2 = $this->content->img2;
            $this->oldImg3 = $this->content->img3;
            $this->RSection = ReportTitleSubtitleSection::findOrFail($id);

        } 

    }

    public function update()
    {
        $this->validate([
            'img1' => 'nullable|image',
            'img2' => 'nullable|image',
            'img3' => 'nullable|image',

        ]);

        // Subir nuevas imágenes si se reemplazaron
        $path1 = $this->img1 ? $this->img1->store('img_cont1', 'public') : $this->oldImg1;
        $path2 = $this->img2 ? $this->img2->store('img_cont2', 'public') : $this->oldImg2;
        $path3 = $this->img3 ? $this->img3->store('img_cont3', 'public') : $this->oldImg3;

        $this->content->update([
            'cont'     => $this->contenido,
            'img1'     => $path1,
            'leyenda1' => $this->leyenda1,
            'img2'     => $path2,
            'leyenda2' => $this->leyenda2,
            'img3'     => $path3,
            'leyenda3' => $this->leyenda3,
        ]);

        session()->flash('cont', '✅ Contenido actualizado correctamente.');
        return redirect()->route('my_reports.addcontenido', ['id' => $this->content->r_t_id ?? $this->content->r_t_s_id ?? $this->content->r_t_s_s_id]);
    }

    public function render()
    {
        return view('livewire.reports.botones-add.editc', [
            'RTitle' => $this->RTitle,
            'RSubtitle' => $this->RSubtitle,
            'RSection' => $this->RSection,
            'boton'  => $this->boton,
            'rp'  => $this->rp,
        ]);
    }
}
