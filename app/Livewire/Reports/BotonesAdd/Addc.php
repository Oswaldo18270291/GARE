<?php

namespace App\Livewire\Reports\BotonesAdd;

use Livewire\Component;
use App\Models\Report;
use App\Models\ReportTitle;
use App\Models\ReportTitleSubtitle;
use App\Models\ReportTitleSubtitleSection;

class Addc extends Component
{
    public $RTitle;
    public $RSubtitle;
    public $RSection;
    public $boton;
    
    public function mount($id, $boton)
    {
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
    public function render()
    {
        return view('livewire.reports.botones-add.addc', [
            'RTitle' => $this->RTitle,
            'RSubtitle' => $this->RSubtitle,
            'RSection' => $this->RSection,
            'boton'  => $this->boton,
        ]);

    }
}
