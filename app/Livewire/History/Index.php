<?php

namespace App\Livewire\History;
use Livewire\WithPagination;
use App\Models\Report;
use Livewire\Component;

class Index extends Component
{
    use WithPagination;
    public function render()
    {
        return view('livewire.history.index',[
            'reports'=> Report::where('status', true)->paginate(10),
        ]);
    }
}
