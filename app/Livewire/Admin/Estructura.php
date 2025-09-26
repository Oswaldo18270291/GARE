<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Title;
use App\Models\Subtitle;
use App\Models\Section;

class Estructura extends Component
{   
public $tree;

    public $titleNames = [];
    public $subtitleNames = [];
    public $sectionNames = [];

    public function mount()
    {
        $this->refreshData();
    }

    private function refreshData()
    {
        $this->tree = Title::with(['subtitles.sections'])
            ->orderBy('id')
            ->get();

        $this->titleNames = [];
        $this->subtitleNames = [];
        $this->sectionNames = [];

        foreach ($this->tree as $t) {
            $this->titleNames[$t->id] = $t->nombre;

            foreach ($t->subtitles()->orderBy('id')->get() as $st) {
                $this->subtitleNames[$st->id] = $st->nombre;

                foreach ($st->sections()->orderBy('id')->get() as $sec) {
                    $this->sectionNames[$sec->id] = $sec->nombre;
                }
            }
        }
    }

    // Guardar cambios
    public function saveTitle($id)
    {
        Title::where('id', $id)->update([
            'nombre' => $this->titleNames[$id] ?? ''
        ]);
    }

    public function saveSubtitle($id)
    {
        Subtitle::where('id', $id)->update([
            'nombre' => $this->subtitleNames[$id] ?? ''
        ]);
    }

    public function saveSection($id)
    {
        Section::where('id', $id)->update([
            'nombre' => $this->sectionNames[$id] ?? ''
        ]);
    }

    public function render()
    {
        return view('livewire.admin.estructura');
    }
}
