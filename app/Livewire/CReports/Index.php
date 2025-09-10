<?php

namespace App\Livewire\CReports;

use App\Models\ReportTitle;
use App\Models\ReportTitleSubtitle;
use App\Models\ReportTitleSubtitleSection;
use App\Models\Title;
use Livewire\Component;

class Index extends Component
{
        public $titles;

    public function mount()
    {
        $this->titles = Title::with('subtitles.sections')->get();
    }


    public function render()
    {
        return view('livewire.c-reports.index');
    }
}
