<?php

namespace App\Livewire\Reports\BotonesAdd;

use Livewire\Component;
use App\Models\Report;

class Addc extends Component
{
    public $report;
    public $boton;
    public function mount($id, $boton)
    {
        $this->report = Report::findOrFail($id);
        $this->boton  = $boton;
  
    }
    public function render()
    {
        return view('livewire.reports.botones-add.addc', [
            'report' => $this->report,
            'boton'  => $this->boton,
        ]);

    }
}
