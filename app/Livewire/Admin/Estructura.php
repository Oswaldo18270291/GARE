<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Title;
use App\Models\Subtitle;
use App\Models\Section;

class Estructura extends Component
{   
public $titulos;

    public $titleNames = [];
    public $subtitleNames = [];
    public $sectionNames = [];

    public function mount()
    {
        $this->refreshData();
    }

    private function refreshData()
    {
        $this->titulos = Title::with(['subtitles.sections'])
            ->orderBy('id')
            ->get();

        $this->titleNames = [];
        $this->subtitleNames = [];
        $this->sectionNames = [];

        foreach ($this->titulos as $t) {
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
        session()->flash('success', '✅ Título actualizado con éxito');
    }

    public function saveSubtitle($id)
    {
        Subtitle::where('id', $id)->update([
            'nombre' => $this->subtitleNames[$id] ?? ''
        ]);
        session()->flash('success', '✅ Subtitulo actualizado con éxito');
    }

    public function saveSection($id)
    {
        Section::where('id', $id)->update([
            'nombre' => $this->sectionNames[$id] ?? '',
            
        ]);
        session()->flash('success', '✅ Sección actualizado con éxito');
    }

        public function addTitle()
    {
        $title = Title::create(['nombre' => 'Nuevo Título']);
        $this->titleNames[$title->id] = $title->nombre;

        $this->refreshData();
        session()->flash('success', '✅ Nuevo título creado');
    }

    public function addSubtitle($titleId)
    {
        $subtitle = Subtitle::create([
            'title_id' => $titleId,
            'nombre'   => 'Nuevo Subtítulo'
        ]);
        $this->subtitleNames[$subtitle->id] = $subtitle->nombre;

        $this->refreshData();
        session()->flash('success', '✅ Nuevo subtítulo creado');
    }

    public function addSection($subtitleId)
    {
        $section = Section::create([
            'subtitle_id' => $subtitleId,
            'nombre'      => 'Nueva Sección'
        ]);
        $this->sectionNames[$section->id] = $section->nombre;

        $this->refreshData();
        session()->flash('success', '✅ Nueva sección creada');
    }

        public function deleteTitle($id)
    {
        Title::findOrFail($id)->delete();
        $this->refreshData();
        session()->flash('success', '🗑️ Título eliminado con todo su contenido.');
    }

    public function deleteSubtitle($id)
    {
        Subtitle::findOrFail($id)->delete();
        $this->refreshData();
        session()->flash('success', '🗑️ Subtítulo eliminado con sus secciones.');
    }

    public function deleteSection($id)
    {
        Section::findOrFail($id)->delete();
        $this->refreshData();
        session()->flash('success', '🗑️ Sección eliminada.');
    }


    public function render()
    {
        return view('livewire.admin.estructura');
    }
}
