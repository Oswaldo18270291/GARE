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
            ->orderBy('orden')
            ->get();

        $this->titleNames = [];
        $this->subtitleNames = [];
        $this->sectionNames = [];

        foreach ($this->titulos as $t) {
            $this->titleNames[$t->id] = $t->nombre;

            foreach ($t->subtitles()->orderBy('orden')->get() as $st) {
                $this->subtitleNames[$st->id] = $st->nombre;

                foreach ($st->sections()->orderBy('orden')->get() as $sec) {
                    $this->sectionNames[$sec->id] = $sec->nombre;
                }
            }
        }
    }

    // =============================
    //  GUARDAR CAMBIOS
    // =============================
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
        session()->flash('success', '✅ Subtítulo actualizado con éxito');
    }

    public function saveSection($id)
    {
        Section::where('id', $id)->update([
            'nombre' => $this->sectionNames[$id] ?? '',
        ]);
        session()->flash('success', '✅ Sección actualizada con éxito');
    }

    // =============================
    //  CREAR NUEVOS ELEMENTOS
    // =============================
    public function addTitle()
    {
        $orden = Title::max('orden') + 1;
        $title = Title::create(['nombre' => 'Nuevo Título', 'orden' => $orden]);
        $this->refreshData();
        session()->flash('success', '✅ Nuevo título creado');
    }

    public function addSubtitle($titleId)
    {
        $orden = Subtitle::where('title_id', $titleId)->max('orden') + 1;
        Subtitle::create([
            'title_id' => $titleId,
            'nombre'   => 'Nuevo Subtítulo',
            'orden'    => $orden
        ]);
        $this->refreshData();
        session()->flash('success', '✅ Nuevo subtítulo creado');
    }

    public function addSection($subtitleId)
    {
        $orden = Section::where('subtitle_id', $subtitleId)->max('orden') + 1;
        Section::create([
            'subtitle_id' => $subtitleId,
            'nombre'      => 'Nueva Sección',
            'orden'       => $orden
        ]);
        $this->refreshData();
        session()->flash('success', '✅ Nueva sección creada');
    }

    // =============================
    //  ELIMINAR ELEMENTOS
    // =============================
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

    // =============================
    //  MOVER ARRIBA / ABAJO
    // =============================
    public function moveUpTitle($id)
    {
        $current = Title::find($id);
        $previous = Title::where('orden', '<', $current->orden)->orderByDesc('orden')->first();
        if ($previous) {
            [$current->orden, $previous->orden] = [$previous->orden, $current->orden];
            $current->save();
            $previous->save();
            $this->refreshData();
        }
    }

    public function moveDownTitle($id)
    {
        $current = Title::find($id);
        $next = Title::where('orden', '>', $current->orden)->orderBy('orden')->first();
        if ($next) {
            [$current->orden, $next->orden] = [$next->orden, $current->orden];
            $current->save();
            $next->save();
            $this->refreshData();
        }
    }

    public function moveUpSubtitle($id)
    {
        $current = Subtitle::find($id);
        $previous = Subtitle::where('title_id', $current->title_id)
            ->where('orden', '<', $current->orden)
            ->orderByDesc('orden')
            ->first();
        if ($previous) {
            [$current->orden, $previous->orden] = [$previous->orden, $current->orden];
            $current->save();
            $previous->save();
            $this->refreshData();
        }
    }

    public function moveDownSubtitle($id)
    {
        $current = Subtitle::find($id);
        $next = Subtitle::where('title_id', $current->title_id)
            ->where('orden', '>', $current->orden)
            ->orderBy('orden')
            ->first();
        if ($next) {
            [$current->orden, $next->orden] = [$next->orden, $current->orden];
            $current->save();
            $next->save();
            $this->refreshData();
        }
    }

    public function moveUpSection($id)
    {
        $current = Section::find($id);
        $previous = Section::where('subtitle_id', $current->subtitle_id)
            ->where('orden', '<', $current->orden)
            ->orderByDesc('orden')
            ->first();
        if ($previous) {
            [$current->orden, $previous->orden] = [$previous->orden, $current->orden];
            $current->save();
            $previous->save();
            $this->refreshData();
        }
    }

    public function moveDownSection($id)
    {
        $current = Section::find($id);
        $next = Section::where('subtitle_id', $current->subtitle_id)
            ->where('orden', '>', $current->orden)
            ->orderBy('orden')
            ->first();
        if ($next) {
            [$current->orden, $next->orden] = [$next->orden, $current->orden];
            $current->save();
            $next->save();
            $this->refreshData();
        }
    }

    // =============================
    //  REUBICAR JERÁRQUICAMENTE
    // =============================
    public function moveSubtitleToTitle($subtitleId, $newTitleId)
    {
        if (!$newTitleId) return;

        $subtitle = Subtitle::find($subtitleId);
        if ($subtitle) {
            $subtitle->title_id = $newTitleId;
            $subtitle->orden = (Subtitle::where('title_id', $newTitleId)->max('orden') ?? 0) + 1;
            $subtitle->save();
            $this->refreshData();
            session()->flash('success', '📂 Subtítulo movido correctamente a otro título.');
        }
    }

    public function moveSectionToSubtitle($sectionId, $newSubtitleId)
    {
        if (!$newSubtitleId) return;

        $section = Section::find($sectionId);
        if ($section) {
            $section->subtitle_id = $newSubtitleId;
            $section->orden = (Section::where('subtitle_id', $newSubtitleId)->max('orden') ?? 0) + 1;
            $section->save();
            $this->refreshData();
            session()->flash('success', '📄 Sección movida correctamente a otro subtítulo.');
        }
    }

    public function render()
    {
        return view('livewire.admin.estructura');
    }
}
