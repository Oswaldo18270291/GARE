<?php

namespace App\Livewire\Admin;
use App\Models\Title;
use Livewire\Component;

class Estructura extends Component
{   
    public $titles;
        public function mount()
    {
        $this->titles = Title::with('subtitles.sections')->get();
    }
    public function render()
    {
        return view('livewire.admin.estructura');
    }
}
