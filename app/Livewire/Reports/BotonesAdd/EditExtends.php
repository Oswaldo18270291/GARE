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
use App\Models\EmpresaCotizacion;
use App\Models\DetalleCotizacion;

class EditExtends extends Component
{
    use WithFileUploads;

    public $boton;
    public $rp;
    public $RTitle;
    public $RSubtitle;
    public $RSection;
    public $bloques = [];
    public $empresas = []; // ✅ para la tabla de cotizaciones

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

        // ===================================================
        // 🔹 Cargar los bloques de contenido
        // ===================================================
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

        // ===================================================
        // 🔹 Cargar las cotizaciones (solo del primer Content)
        // ===================================================
        if ($contents->count() > 0) {
            $primerContent = $contents->first();
            $empresas = EmpresaCotizacion::with(['detalles' => function ($q) {
                    $q->orderBy('orden'); // ✅ ordena las filas de cada empresa
                }])
                ->where('content_id', $primerContent->id)
                ->orderBy('orden') // ✅ ordena las empresas
                ->get();

            $this->empresas = $empresas->map(function ($empresa) {
                return [
                    'id' => $empresa->id,
                    'nombre' => $empresa->nombre,
                    'color' => $empresa->color,
                    'items' => $empresa->detalles->map(fn($d) => [
                        'id' => $d->id,
                        'concepto' => $d->concepto,
                        'cantidad' => $d->cantidad,
                        'costo' => $d->costo,
                        'comentarios' => $d->comentarios,
                    ])->toArray(),
                ];
            })->toArray();
        }
    }

    // ===================================================
    // 🔹 Funciones para editar tabla de cotizaciones
    // ===================================================

    public function agregarEmpresa()
    {
        $this->empresas[] = [
            'id' => null,
            'nombre' => '',
            'color' => '#ffffff',
            'items' => [
                ['id' => null, 'concepto' => '', 'cantidad' => '', 'costo' => '', 'comentarios' => ''],
            ],
        ];
    }

    public function eliminarEmpresa($index)
    {
        $empresa = $this->empresas[$index];
        if (!empty($empresa['id'])) {
            EmpresaCotizacion::where('id', $empresa['id'])->delete();
        }
        unset($this->empresas[$index]);
        $this->empresas = array_values($this->empresas);
    }

    public function agregarItem($eIndex)
    {
        $this->empresas[$eIndex]['items'][] = [
            'id' => null,
            'concepto' => '',
            'cantidad' => '',
            'costo' => '',
            'comentarios' => '',
        ];
    }

    public function eliminarItem($eIndex, $iIndex)
    {
        $item = $this->empresas[$eIndex]['items'][$iIndex];
        if (!empty($item['id'])) {
            DetalleCotizacion::where('id', $item['id'])->delete();
        }
        unset($this->empresas[$eIndex]['items'][$iIndex]);
        $this->empresas[$eIndex]['items'] = array_values($this->empresas[$eIndex]['items']);
    }

    // ===================================================
    // 🔹 Actualizar bloques y cotizaciones
    // ===================================================
   public function update()
    {
        foreach ($this->bloques as $bloqueIndex => $bloque) {
            $content = Content::find($bloque['id']);
            if (!$content) continue;

            $imagenesFinales = [];

            foreach ($bloque['imagenes'] as $imgIndex => $img) {

                $ruta = $img['src'];
                $leyenda = $img['leyenda'];

                // Reemplazo de imagen
                if (!empty($img['nuevo'])) {
                    if (!Storage::disk('public')->exists('img_extends')) {
                        Storage::disk('public')->makeDirectory('img_extends');
                    }

                    if (!empty($ruta)) {
                        Storage::disk('public')->delete($ruta);
                    }

                    $filename = "bloque{$bloqueIndex}_img{$imgIndex}_" . uniqid() . "." .
                        $img['nuevo']->getClientOriginalExtension();

                    $ruta = $img['nuevo']->storeAs('img_extends', $filename, 'public');
                }

                $imagenesFinales[] = [
                    'src' => $ruta,
                    'leyenda' => $leyenda,
                    'orden_imagen' => $imgIndex + 1,
                ];
            }

            $content->update([
                'cont' => $bloque['contenido'],
                'img1' => json_encode($imagenesFinales, JSON_UNESCAPED_UNICODE),
                'orden' => $bloqueIndex + 1,
                'bloque_num' => $bloqueIndex + 1,
            ]);
        }

        // ============================================================
        // Actualizar cotizaciones como antes
        // ============================================================

        $primerContent = $this->bloques[0]['id'] ?? null;

        if ($primerContent) {
            foreach ($this->empresas as $eIndex => $empresa) {

                $empresaModel = EmpresaCotizacion::updateOrCreate(
                    ['id' => $empresa['id']],
                    [
                        'content_id' => $primerContent,
                        'nombre' => $empresa['nombre'],
                        'color' => $empresa['color'],
                        'orden' => $eIndex + 1,
                    ]
                );

                foreach ($empresa['items'] as $iIndex => $item) {
                    DetalleCotizacion::updateOrCreate(
                        ['id' => $item['id']],
                        [
                            'empresa_id' => $empresaModel->id,
                            'concepto' => $item['concepto'],
                            'cantidad' => $item['cantidad'],
                            'costo' => $item['costo'],
                            'comentarios' => $item['comentarios'],
                            'orden' => $iIndex + 1,
                        ]
                    );
                }
            }
        }

        session()->flash('cont', 'Contenido actualizado correctamente.');
        return redirect()->route('my_reports.addcontenido', ['id' => $this->rp]);
    }

        public function agregarBloque()
    {
        $this->bloques[] = [
            'contenido' => '',
            'imagenes' => [
                ['leyenda' => '', 'img' => null],
            ],
        ];
    }

    /** 🗑 Eliminar bloque */
    public function eliminarBloque($index)
    {
        unset($this->bloques[$index]);
        $this->bloques = array_values($this->bloques);
    }

    /** ➕ Agregar imagen */
    public function agregarImagen($bloqueIndex)
    {
        $this->bloques[$bloqueIndex]['imagenes'][] = [
            'leyenda' => '',
            'img' => null,
        ];
    }

    /** 🗑 Eliminar imagen */
    public function eliminarImagen($bloqueIndex, $imagenIndex)
    {
        unset($this->bloques[$bloqueIndex]['imagenes'][$imagenIndex]);
        $this->bloques[$bloqueIndex]['imagenes'] = array_values($this->bloques[$bloqueIndex]['imagenes']);
    }

    public function render()
    {
        return view('livewire.reports.botones-add.edit-extends', [
            'RTitle' => $this->RTitle,
            'RSubtitle' => $this->RSubtitle,
            'RSection' => $this->RSection,
            'boton' => $this->boton,
            'rp' => $this->rp,
        ]);
    }
}
