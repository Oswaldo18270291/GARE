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

     public function save()
    {
        foreach ($this->selectedTitles as $titleId) {
            $reportTitle = ReportTitle::create([
                'report_id' => $this->reportId,
                'title_id' => $titleId,
                'status' => true,
            ]);

            $subtitles = $this->selectedSubtitles[$titleId] ?? [];
            foreach ($subtitles as $subtitleId) {
                $reportSubtitle = ReportTitleSubtitle::create([
                    'r_t_id' => $reportTitle->id,
                    'subtitle_id' => $subtitleId,
                    'status' => true,
                ]);

                $sections = $this->selectedSections[$subtitleId] ?? [];
                foreach ($sections as $sectionId) {
                    ReportTitleSubtitleSection::create([
                        'r_t_s_id' => $reportSubtitle->id,
                        'section_id' => $sectionId,
                        'status' => true,
                    ]);
                }
            }
        }

        session()->flash('success', 'Guardado correctamente.');
        $this->reset(['selectedTitles', 'selectedSubtitles', 'selectedSections']);
    }

    public function render()
    {
        return view('livewire.c-reports.index');
    }
}
