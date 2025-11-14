<?php

namespace App\Livewire\Reports\BotonesAdd;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\AnalysisDiagram;
use App\Models\Subtitle;
use App\Models\Report;
use App\Models\Content;
use App\Models\DetalleCotizacion;
use App\Models\EmpresaCotizacion;
use App\Models\Foda;
use App\Models\ReportTitle;
use App\Models\ReportTitleSubtitle;
use App\Models\ReportTitleSubtitleSection;
use App\Models\MentalMap;
use App\Models\OrganigramaControl;
use Illuminate\Support\Facades\Storage;

class AddcExtends extends Component
{
    use WithFileUploads;
    public $empresas = [];
    public $RTitle;
    public $RSubtitle;
    public $RSection;
    public $boton;
    public $report;
    public $rp;
    public $bloques = [];
    public $risks;
    public $rep;
    public $su;

    public function mount($id, $boton, $rp)
    {
        $report = Report::findOrFail($rp);
        $this->authorize('update', $report);

        $this->rep = $report;
        $this->boton = $boton;
        $this->rp = $rp;

        // Identificar qué entidad está editando
        if ($boton == 'tit') {
            $this->RTitle = ReportTitle::findOrFail($id);
        } elseif ($boton == 'sub') {
            $this->RSubtitle = ReportTitleSubtitle::findOrFail($id);
        } elseif ($boton == 'sec') {
            $this->RSection = ReportTitleSubtitleSection::findOrFail($id);
        }
        $this->empresas = [];
        // Inicializa con un bloque base
        $this->bloques = [
            [
                'contenido' => '',
                'imagenes' => [
                    ['leyenda' => '', 'img' => null],
                ],
            ],
        ];
    }

    /** ➕ Agregar bloque */
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



public function store($id, $boton, $rp)
{
    $report = Report::findOrFail($rp);
    $this->authorize('update', $report);

    $primerContent = null; // 🔹 Aquí guardaremos el primer Content creado

    // =======================================================
    // 🔹 1️⃣ Guardar todos los bloques normalmente
    // =======================================================
    foreach ($this->bloques as $bloqueIndex => $bloque) {
        $contenido = $bloque['contenido'] ?? '';
        $imagenes = [];

        foreach ($bloque['imagenes'] as $imagenIndex => $imagen) {
            $ruta = null;
            $leyenda = $imagen['leyenda'] ?? null;

            if (
                !empty($imagen['img']) &&
                $imagen['img'] instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile
            ) {
                if (!Storage::disk('public')->exists('img_extends')) {
                    Storage::disk('public')->makeDirectory('img_extends');
                }

                $filename = 'bloque' . ($bloqueIndex + 1) . '_img' . ($imagenIndex + 1) . '_' . uniqid() . '.' . $imagen['img']->getClientOriginalExtension();
                $ruta = $imagen['img']->storeAs('img_extends', $filename, 'public');
            }

            $imagenes[] = [
                'src' => $ruta,
                'leyenda' => $leyenda,
                'orden_imagen' => $imagenIndex + 1,
            ];
        }

        // Datos del bloque
        $data = [
            'cont' => $contenido,
            'img_block' => json_encode($imagenes, JSON_UNESCAPED_UNICODE),
            'orden' => $bloqueIndex + 1,
            'bloque_num' => $bloqueIndex + 1,
        ];

        // Relación según el tipo
        if ($boton == 'tit' && $this->RTitle) {
            $data['r_t_id'] = $this->RTitle->id;
        } elseif ($boton == 'sub' && $this->RSubtitle) {
            $data['r_t_s_id'] = $this->RSubtitle->id;
        } elseif ($boton == 'sec' && $this->RSection) {
            $data['r_t_s_s_id'] = $this->RSection->id;
        }

        // Guardar bloque
        $content = Content::create($data);

        // 🔹 Solo si es el primer bloque, lo guardamos para usarlo en las cotizaciones
        if ($bloqueIndex === 0) {
            $primerContent = $content;
        }
    }

    // =======================================================
    // 🔹 2️⃣ Guardar las cotizaciones SOLO UNA VEZ
    // =======================================================
    if ($primerContent) {
        // Eliminar cotizaciones anteriores para ese Content
        EmpresaCotizacion::where('content_id', $primerContent->id)->delete();

            foreach ($this->empresas as $eIndex => $empresa) {
                $empresaModel = EmpresaCotizacion::create([
                    'content_id' => $primerContent->id,
                    'nombre' => $empresa['nombre'],
                    'color' => $empresa['color'],
                    'orden' => $eIndex + 1, // ✅ guardar orden
                ]);

            // Crear filas (detalles)
            foreach ($empresa['items'] as $iIndex => $item) {
                DetalleCotizacion::create([
                    'empresa_id' => $empresaModel->id,
                    'concepto' => $item['concepto'],
                    'cantidad' => $item['cantidad'],
                    'costo' => $item['costo'],
                    'comentarios' => $item['comentarios'],
                    'orden' => $iIndex + 1, // ✅ guardar orden dentro de la empresa
                ]);
            }
        }
    }

    // =======================================================
    // 🔹 3️⃣ Finalizar
    // =======================================================
    session()->flash('cont', 'Se agregó el contenido y las cotizaciones correctamente.');
    $this->redirectRoute('my_reports.addcontenido', ['id' => $report->id], navigate: true);
}


    public function agregarEmpresa()
    {
        $this->empresas[] = [
            'nombre' => '',
            'color' => '#ffffff',
            'items' => [
                [
                    'concepto' => '',
                    'cantidad' => '',
                    'costo' => '',
                    'comentarios' => '',
                ]
            ],
        ];
    }

    // 🔹 Agregar una fila de producto a una empresa
    public function agregarItem($index)
    {
        $this->empresas[$index]['items'][] = [
            'concepto' => '',
            'cantidad' => '',
            'costo' => '',
            'comentarios' => '',
        ];
    }

    // 🔹 Eliminar fila específica dentro de una empresa
    public function eliminarItem($empresaIndex, $itemIndex)
    {
        unset($this->empresas[$empresaIndex]['items'][$itemIndex]);
        $this->empresas[$empresaIndex]['items'] = array_values($this->empresas[$empresaIndex]['items']);
    }

    // 🔹 Eliminar empresa completa
    public function eliminarEmpresa($index)
    {
        unset($this->empresas[$index]);
        $this->empresas = array_values($this->empresas);
    }



    public function render()
    {
        return view('livewire.reports.botones-add.addc-extends', [
            'RTitle' => $this->RTitle,
            'RSubtitle' => $this->RSubtitle,
            'RSection' => $this->RSection,
            'boton' => $this->boton,
            'rp' => $this->rp,
            'risks' => $this->risks,
            'rep' => $this->rep,
            'su' => $this->su,
        ]);
    }
}
