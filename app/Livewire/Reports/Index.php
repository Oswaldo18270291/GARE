<?php

namespace App\Livewire\Reports;
use Livewire\WithPagination;
use App\Models\Report;
use Livewire\Component;

class Index extends Component
{
    use WithPagination;
    public function render()
    {
        return view('livewire.reports.index',[
            'reports'=> Report::paginate(10)
        ]);
    }

}
