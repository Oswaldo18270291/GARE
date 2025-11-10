<?php

namespace App\Livewire\Reports\BotonesAdd;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\Content;
use App\Models\ReportTitle;
use App\Models\ReportTitleSubtitle;
use App\Models\ReportTitleSubtitleSection;
use App\Models\Report;
use App\Models\Subtitle;

class EditExtends extends Component
{
    use WithFileUploads;

    public $boton;
    public $rp;
    public $RTitle;
    public $RSubtitle;
    public $RSection;
    public $bloques = [];

    public function mount($id, $boton, $rp)
    {
        $this->boton = $boton;
        $this->rp = $rp;

        // 🔹 Determinar relación
        if ($boton === 'tit') {
            $this->RTitle = ReportTitle::findOrFail($id);
            $contents = Content::where('r_t_id', $id)->orderBy('bloque_num')->get();
        } elseif ($boton === 'sub') {
            $this->RSubtitle = ReportTitleSubtitle::findOrFail($id);
            $contents = Content::where('r_t_s_id', $id)->orderBy('bloque_num')->get();
        } elseif ($boton === 'sec') {
            $this->RSection = ReportTitleSubtitleSection::findOrFail($id);
            $contents = Content::where('r_t_s_s_id', $id)->orderBy('bloque_num')->get();
        } else {
            $contents = collect();
        }

        // 🔹 Convertir cada contenido en bloque editable
        foreach ($contents as $content) {
            $imgs = json_decode($content->img1, true) ?? [];

            $imagenes = [];
            foreach ($imgs as $img) {
                $imagenes[] = [
                    'src' => $img['src'] ?? null,
                    'leyenda' => $img['leyenda'] ?? '',
                    'nuevo' => null,
                ];
            }

            $this->bloques[] = [
                'id' => $content->id,
                'contenido' => $content->cont ?? '',
                'imagenes' => $imagenes,
            ];
        }
    }

    public function agregarImagen($bloqueIndex)
    {
        $this->bloques[$bloqueIndex]['imagenes'][] = [
            'src' => null,
            'leyenda' => '',
            'nuevo' => null,
        ];
    }

    public function eliminarImagen($bloqueIndex, $imagenIndex)
    {
        $img = $this->bloques[$bloqueIndex]['imagenes'][$imagenIndex];

        if (!empty($img['src'])) {
            Storage::disk('public')->delete($img['src']);
        }

        unset($this->bloques[$bloqueIndex]['imagenes'][$imagenIndex]);
        $this->bloques[$bloqueIndex]['imagenes'] = array_values($this->bloques[$bloqueIndex]['imagenes']);
    }

    public function update()
    {
        foreach ($this->bloques as $bloque) {
            $content = Content::find($bloque['id']);
            if (!$content) continue;

            $imagenesFinales = [];

            foreach ($bloque['imagenes'] as $img) {
                $ruta = $img['src'];
                $leyenda = $img['leyenda'];

                if (!empty($img['nuevo'])) {
                    if (!Storage::disk('public')->exists('img_extends')) {
                        Storage::disk('public')->makeDirectory('img_extends');
                    }

                    // Borra imagen vieja si existe
                    if (!empty($ruta)) {
                        Storage::disk('public')->delete($ruta);
                    }

                    $filename = uniqid('img_') . '.' . $img['nuevo']->getClientOriginalExtension();
                    $ruta = $img['nuevo']->storeAs('img_extends', $filename, 'public');
                }

                $imagenesFinales[] = [
                    'src' => $ruta,
                    'leyenda' => $leyenda,
                ];
            }

            $content->update([
                'cont' => $bloque['contenido'],
                'img1' => json_encode($imagenesFinales, JSON_UNESCAPED_UNICODE),
            ]);
        }
        session()->flash('cont', '✅ Todos los bloques fueron actualizados correctamente.');
        return redirect()->route('my_reports.addcontenido', ['id' =>$this->rp]);
        
    }

    public function render()
    {
        return view('livewire.reports.botones-add.edit-extends', [
            'RTitle' => $this->RTitle,
            'RSubtitle' => $this->RSubtitle,
            'RSection' => $this->RSection,
            'boton'  => $this->boton,
            'rp'  => $this->rp,

        ]);
    }
}
