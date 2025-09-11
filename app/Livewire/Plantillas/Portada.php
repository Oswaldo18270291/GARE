<?php

namespace App\Livewire\Plantillas;
use App\Models\Report;

use Livewire\Component;

class Portada extends Component
{



    public function render()
    {
        return view('livewire.plantillas.portada',[
            'reports'=> Report::all()
        ]);
    }
}
