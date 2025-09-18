<?php

namespace App\Livewire\Reports\BotonesAdd;

use Livewire\Component;
use App\Models\Report;
use App\Models\ReportTitle;

class Addc extends Component
{
    public $RTitle;
    public $boton;
    
    public function mount($id, $boton)
    {
        $this->RTitle = ReportTitle::findOrFail($id);
        $boton  = $boton;
    }
    public function render()
    {
        return view('livewire.reports.botones-add.addc', [
            'RTitle' => $this->RTitle,
            'boton'  => $this->boton,
        ]);

    }
}
