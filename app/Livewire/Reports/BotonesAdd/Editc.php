<?php

namespace App\Livewire\Reports\BotonesAdd;

use App\Models\AccionSeguridad;
use App\Models\AnalysisDiagram;
use Livewire\Component;
use App\Models\Content;
use App\Models\ContentReference;
use App\Models\Foda;
use App\Models\OrganigramaControl;
use Livewire\WithFileUploads;
use App\Models\ReportTitle;
use App\Models\ReportTitleSubtitle;
use App\Models\ReportTitleSubtitleSection;
use App\Models\Report;
use App\Models\Subtitle;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

class Editc extends Component
{
    public $contentId; 
    use WithFileUploads;
    public $RTitle;
    public $RSubtitle;
    public $RSection;
    public $content;
    public $contenido;
    public $contenido_a_p;
    public $contenido_m_p_a;
    public $img1;
    public $img2;
    public $img3;
    public $leyenda1;
    public $leyenda2;
    public $leyenda3;
    public $grafica;
    public $boton;
    public $rp;
    // Para previsualizar las imágenes antiguas
    public $oldImg1;
    public $oldImg2;
    public $oldImg3;

    public $mosler;
    public $c;
    public $risks;
    public $riesgos = [];
    public $rep;
    public $riesgs;
    public $que;
    public $como;
    public $quien;
    public $por_que;
    public $donde;
    public $cuanto;
    public $de;
    public $hasta;
    public $acciones_planes;
    public $medidas_p;
    
    public $nodos = [];
    public $relaciones = [];
    public $backgroundImage = null;
    public $background_opacity = 0.4;

    public $fortalezas;
    public $debilidades;
    public $oportunidades;
    public $amenazas;

    public $puesto_r;
    public $nombre_r;
    public $puesto_e;
    public $nombre_e;
    public $puesto_c;
    public $nombre_c;
    public $referencias = [];
    public $acciones;
    public $referenciasNuevas = [];

    public function mount($id,$boton,$rp)
    {
    $report = Report::findOrFail($rp);
    $this->authorize('update', $report); 

    $this->rep = Report::findOrFail($rp);
    $this->authorize('update', $report); // 👈 ahora sí se evalúa la policy
        if($boton == 'tit'){
        $this->content = Content::with('referencias')->where('r_t_id', $id)->first();
            // Cargamos valores existentes
            
            $this->contenido = $this->content->cont;
            $this->leyenda1 = $this->content->leyenda1;
            $this->leyenda2 = $this->content->leyenda2;
            $this->leyenda3 = $this->content->leyenda3;

            $this->oldImg1 = $this->content->img1;
            $this->oldImg2 = $this->content->img2;
            $this->oldImg3 = $this->content->img3;
            $this->RTitle = ReportTitle::findOrFail($id);
        } elseif($boton == 'sub'){
            $this->RTitle = null;
            $this->content = Content::with('referencias')->where('r_t_s_id', $id)->first();
    if ($this->content) {
        // Si es el subtítulo 16 (criterios de evaluación)
        $subtitleId = ReportTitleSubtitle::where('id', $id)->value('subtitle_id');
        if ($subtitleId == 32) {
            $this->riesgos = AnalysisDiagram::where('content_id', $this->content->id)
                ->orderBy('no')
                ->get()
                ->map(function ($r) {
                    return [
                        'id' => $r->id,
                        'no' => $r->no,
                        'riesgo' => $r->riesgo,
                        'impacto_f' => $r->impacto_f,
                        'impacto_o' => $r->impacto_o,
                        'extension_d' => $r->extension_d,
                        'probabilidad_m' => $r->probabilidad_m,
                        'impacto_fin' => $r->impacto_fin,
                        'cal' => $r->cal,
                        'clase_riesgo' => $r->clase_riesgo,
                        'factor_oc' => $r->factor_oc,
                    ];
                })
                ->toArray();
                $mentalMap = \App\Models\MentalMap::where('content_id', $this->content->id)->first();
                if ($mentalMap) {
                    $this->nodos = $mentalMap->nodos ?? [];
                    $this->relaciones = $mentalMap->relaciones ?? [];
                    $this->backgroundImage = $mentalMap->background_image ?? null;
                    $this->background_opacity = $mentalMap->background_opacity ?? 0.4;
                    $this->dispatch('actualizarMapa', nodos: $this->nodos, relaciones: $this->relaciones);

                }
        }
    }else 
        {
            $this->riesgos = [];
        }
    if ($subtitleId == 18) {
    $this->riesgs = OrganigramaControl::where('content_id', $this->content->id)->get()->toArray();

    }
    if ($subtitleId == 33) {
    $this->riesgs = Foda::where('content_id', $this->content->id)->first();
        $this->fortalezas = $this->riesgs->fortalezas;
        $this->debilidades = $this->riesgs->debilidades;
        $this->oportunidades = $this->riesgs->oportunidades;
        $this->amenazas = $this->riesgs->amenazas;
    } if ($subtitleId === 38 ) {
                $contenido = Content::where('r_t_s_id', $id)->first();
                if ($contenido && AccionSeguridad::where('content_id', $contenido->id)->exists()) {
                    // Ya hay registros guardados → los traemos agrupados por sección
                    $acciones = AccionSeguridad::where('content_id', $contenido->id)
                        ->orderBy('no')
                        ->get()
                        ->groupBy('seccion')
                        ->toArray();

                    // Formatear a estructura esperada (por índice)
                    $this->acciones = [];
                    foreach ($acciones as $seccion => $items) {
                        $this->acciones[$seccion] = array_map(function ($item) {
                            return [
                                'no'       => $item['no'],
                                'tema'     => $item['tema'],
                                'accion'   => $item['accion'],
                                't_costo'  => $item['t_costo'],
                                'nivel_p'  => $item['nivel_p'],
                            ];
                        }, $items);
                    }
                }
            }
            $this->rep->titles = ReportTitle::where('report_id', $this->rep->id)->where('status',1)->get();
            // Cargamos valores existentes
            foreach ($this->rep->titles as $title) 
            {
            if( ReportTitleSubtitle::where('r_t_id', $title->id)->where('subtitle_id', 32)->exists()){
            $mosler = ReportTitleSubtitle::where('r_t_id', $title->id)->where('subtitle_id', 32)->first();  
                if( Content::where('r_t_s_id',$mosler->id)->exists()){
                    $c = Content::where('r_t_s_id',$mosler->id)->first();
                    $this->risks = AnalysisDiagram::where('content_id',$c->id)->get();
                }

             }
            }
            $this->cargarRiesgos();
            $this->contenido = $this->content->cont;
            $this->contenido_m_p_a = $this->content->contenido_m_p_a;
            $this->contenido_a_p = $this->content->contenido_a_p;
            $this->leyenda1 = $this->content->leyenda1;
            $this->leyenda2 = $this->content->leyenda2;
            $this->leyenda3 = $this->content->leyenda3;

            $this->oldImg1 = $this->content->img1;
            $this->oldImg2 = $this->content->img2;
            $this->oldImg3 = $this->content->img3;
            $this->grafica = $this->content->grafica;
            $this->que = $this->content->que;
            $this->como = $this->content->como;
            $this->quien = $this->content->quien;
            $this->por_que = $this->content->por_que;
            $this->donde = $this->content->donde;
            $this->cuanto = $this->content->cuanto;
            $this->de = $this->content->de;
            $this->hasta = $this->content->hasta;

            $this->puesto_r = $this->content->puesto_r;
            $this->nombre_r = $this->content->nombre_r;
            $this->puesto_e = $this->content->puesto_e;
            $this->nombre_e = $this->content->nombre_e;
            $this->puesto_c = $this->content->puesto_c;
            $this->nombre_c = $this->content->nombre_c;
            

            $this->RSubtitle = ReportTitleSubtitle::findOrFail($id);
            $this->dispatch('actualizarMapa', nodos: $this->nodos, relaciones: $this->relaciones);
        } else if($boton == 'sec'){
            $this->RTitle = null;
            $this->RSubtitle = null;
            $this->content = Content::with('referencias')->where('r_t_s_s_id', $id)->first();
            // Cargamos valores existentes
            
            $this->contenido = $this->content->cont;
            $this->leyenda1 = $this->content->leyenda1;
            $this->leyenda2 = $this->content->leyenda2;
            $this->leyenda3 = $this->content->leyenda3;

            $this->oldImg1 = $this->content->img1;
            $this->oldImg2 = $this->content->img2;
            $this->oldImg3 = $this->content->img3;
            $this->RSection = ReportTitleSubtitleSection::findOrFail($id);
            

        } 
            $this->contentId = $this->content->id; // 👈 aquí lo asignas
            $this->contenido =  $this->content->cont;
            $this->referencias = $this->content->referencias->map(fn($r) => [
                'num' => $r->numero,
                'texto' => $r->texto,
            ])->toArray();

    }

    private function formatNo($n)
    {
        return 'R' . str_pad($n, 2, '0', STR_PAD_LEFT);
    }

    private function renumerar()
    {
        foreach ($this->riesgos as $i => &$r) {
            $r['no'] = $this->formatNo($i + 1);
        }
    }

    public function addFila()
    {
        $this->riesgos[] = [
            'no' => $this->formatNo(count($this->riesgos) + 1),
            'riesgo' => '',
            'f' => 1, 's' => 1, 'p' => 1,
            'e' => 1, 'pb' => 1, 'if' => 1,
        ];
    }

    public function removeFila($index)
    {
        // 1️⃣ Obtener el riesgo correspondiente
        $riesgo = $this->riesgos[$index];

        // 2️⃣ Si existe en base de datos, eliminarlo
        if (isset($riesgo['id'])) {
            \App\Models\AnalysisDiagram::where('id', $riesgo['id'])->delete();
        }

        // 3️⃣ Eliminar de la lista en memoria
        unset($this->riesgos[$index]);
        $this->riesgos = array_values($this->riesgos);

        // 4️⃣ Reordenar si es necesario
        $this->renumerar();
    }


    public function updateRiesgos($contentId)
{
    $now = now();

    foreach ($this->riesgos as $index => $r) {

        // ❗ Ignorar filas vacías o sin nombre de riesgo
        if (empty($r['riesgo'])) {
            continue;
        }

        $data = [
            'no'           => $r['no'],
            'riesgo'       => trim($r['riesgo']),
            'f'            => (int)($r['f'] ?? 1),
            's'            => (int)($r['s'] ?? 1),
            'p'            => (int)($r['p'] ?? 1),
            'e'            => (int)($r['e'] ?? 1),
            'pb'           => (int)($r['pb'] ?? 1),
            'if'           => (int)($r['if'] ?? 1),
            'f_ocurrencia' => $this->calcularFOcurrencia($r),
            'orden'        => $index + 1,
            'updated_at'   => $now,
        ];

        if (isset($r['id'])) {
            // 🔁 Actualizar
            AnalysisDiagram::where('id', $r['id'])->update($data);
        } else {
            // ➕ Crear solo si tiene contenido válido
            $data['content_id'] = $contentId;
            $data['created_at'] = $now;
            AnalysisDiagram::create($data);
        }
    }

    session()->flash('cont', '✅ Riesgos actualizados correctamente.');
}

public function updateRiesgosEvaluacion($contentId)
{
    $now = now();

    foreach ($this->riesgos as $index => $r) {
        if (empty($r['riesgo'])) continue;

        $data = [
            'no' => $r['no'],
            'riesgo' => trim($r['riesgo']),
            'impacto_f' => (int)($r['impacto_f'] ?? 1),
            'impacto_o' => (int)($r['impacto_o'] ?? 1),
            'extension_d' => (int)($r['extension_d'] ?? 1),
            'probabilidad_m' => (int)($r['probabilidad_m'] ?? 1),
            'impacto_fin' => (int)($r['impacto_fin'] ?? 1),
            'cal' => (int)($r['cal'] ?? 0),
            'clase_riesgo' => $r['clase_riesgo'] ?? '',
            'factor_oc' => $r['factor_oc'] ?? 0,
            'orden' => $index + 1,
            'updated_at' => $now,
        ];

        if (isset($r['id'])) {
            AnalysisDiagram::where('id', $r['id'])->update($data);
        } else {
            $data['content_id'] = $contentId;
            $data['created_at'] = $now;
            AnalysisDiagram::create($data);
        }
    }

    session()->flash('success', '✅ Riesgos de evaluación actualizados correctamente.');
}


    public function update($id,$boton,$rp)
    {

        $this->validate([
            'img1' => 'nullable|image',
            'img2' => 'nullable|image',
            'img3' => 'nullable|image',

        ]);
        
        // Subir nuevas imágenes si se reemplazaron
        $path1 = $this->img1 ? $this->img1->store('img_cont1', 'public') : $this->oldImg1;
        $path2 = $this->img2 ? $this->img2->store('img_cont2', 'public') : $this->oldImg2;
        $path3 = $this->img3 ? $this->img3->store('img_cont3', 'public') : $this->oldImg3;

        $data = [
            'cont'     => $this->contenido,
            'img1'     => $path1,
            'leyenda1' => $this->leyenda1,
            'img2'     => $path2,
            'leyenda2' => $this->leyenda2,
            'img3'     => $path3,
            'leyenda3' => $this->leyenda3,
        ];
       

        // Solo agrega el campo "grafica" si el subtitle_id es 16
        if ($boton === 'sub') {
            $nl = ReportTitleSubtitle::findOrFail($id);
            if ($nl->subtitle_id === 32) {
                $data['grafica'] = $this->grafica;
                $this->guardarRiesgos();
                $this->guardarMapaMental();
            }            
            if ($nl->subtitle_id === 16) {
                $data['grafica'] = $this->grafica;
            }
            if ($nl->subtitle_id === 17) {
                $data['contenido_m_p_a'] = $this->contenido_m_p_a;
                $data['contenido_a_p'] = $this->contenido_a_p;
            }if ($nl->subtitle_id === 42 ) {
                $data['puesto_r'] = $this->puesto_r;
                $data['nombre_r'] = $this->nombre_r;
                $data['puesto_e'] = $this->puesto_e;
                $data['nombre_e'] = $this->nombre_e;
                $data['puesto_c'] = $this->puesto_c;
                $data['nombre_c'] = $this->nombre_c;
            }
            if ($nl->subtitle_id === 18) {
                foreach ($this->riesgs as $r) {
                        if (isset($r['id'])) {
                            OrganigramaControl::where('id', $r['id'])->update([
                                'medidas_p' => $r['medidas_p'] ?? null,
                                'acciones_planes' => $r['acciones_planes'] ?? null,
                            ]);
                        }
                    }

            }
            if ($nl->subtitle_id === 33) {
                 Foda::where('id', $this->riesgs->id)->update([
                                'fortalezas'    =>  $this->fortalezas,
                                'debilidades'   =>  $this->debilidades,
                                'oportunidades' =>  $this->oportunidades,
                                'amenazas'       =>  $this->amenazas,
                            ]);
            }if ($nl->subtitle_id === 38) {
                $now = now();
                $contenido = Content::where('r_t_s_id', $nl->id)->first();

                if ($contenido) {
                    // 🔹 Recorremos todas las secciones y temas del arreglo Livewire
                    foreach ($this->acciones as $seccion => $temas) {
                        foreach ($temas as $r) {
                            // Intentamos encontrar el registro existente
                            $accion = \App\Models\AccionSeguridad::where('content_id', $contenido->id)
                                ->where('no', $r['no'])
                                ->where('tema', $r['tema'])
                                ->first();

                            if ($accion) {
                                // 🔸 Actualizar registro existente
                                $accion->update([
                                    'accion'   => $r['accion'] ?? null,
                                    't_costo'  => $r['t_costo'] ?? null,
                                    'nivel_p'  => $r['nivel_p'] ?? null,
                                    'updated_at' => $now,
                                ]);
                            }
                        }
                    }
                }
            
            }
            $name = Subtitle::where('id', $nl->subtitle_id)->value('id');
            if (in_array($name, [20, 21, 22, 23, 24, 25, 26, 27, 28, 29])) {
            $data['que']    = $this->que;
            $data['como']   = $this->como;
            $data['quien']  = $this->quien;
            $data['por_que']= $this->por_que;
            $data['donde']  = $this->donde;
            $data['cuanto'] = $this->cuanto;
            $data['de']     = $this->de;
            $data['hasta']  = $this->hasta;
        }
        }
        // Ahora actualiza el modelo
        $this->content->update($data);
        // ✅ Actualizar referencias solo si hay cambios reales
        if (!empty($this->referenciasNuevas)) {
            foreach ($this->referenciasNuevas as $ref) {
                // Verifica que no exista ya antes de crear
                $existe = ContentReference::where('content_id', $this->content->id)
                    ->where('numero', $ref['num'])
                    ->exists();

                if (! $existe) {
                    ContentReference::create([
                        'content_id' => $this->content->id,
                        'numero'     => $ref['num'],
                        'texto'      => $ref['texto'],
                    ]);
                }
            }

            // Limpia las temporales
            $this->referenciasNuevas = [];
        }

        session()->flash('cont', '✅ Contenido actualizado correctamente.');
        return redirect()->route('my_reports.addcontenido', ['id' =>$rp]);
    }

     #[On('guardarOrden')]
    public function guardarOrden($risks)
    {
        foreach ($risks as $risk) {
            AnalysisDiagram::where('id', $risk['id'])->update([
                'orden' => $risk['orden'],
                'tipo_riesgo' => $risk['tipo_riesgo'],
            ]);
        }

        // 🔄 Recargar la colección después de guardar
        $this->cargarRiesgos();
    }

    private function cargarRiesgos()
    {
        $this->risks;

        
    }

    #[On('guardarOrden2')]
public function guardarOrden2($risks)
{
    foreach ($risks as $risk) {
        AnalysisDiagram::where('id', $risk['id'])->update([
            'orden2' => $risk['orden2'],
            'c_riesgo' => $risk['c_riesgo'],
        ]);
    }

    $this->cargarRiesgos2();
}

    private function cargarRiesgos2()
    {
        $this->risks = AnalysisDiagram::where('content_id', $this->c->id ?? null)->get();
    }

    
     public function calcularFOcurrencia($riesgo)
    {
        // Ejemplo: promedio ponderado (ajusta según tu fórmula real)
        return round((($riesgo['f'] + $riesgo['s'] + $riesgo['p'] + $riesgo['e'] + $riesgo['pb'] + $riesgo['if']) / 6) * 18, 2);
    }

    public function claseRiesgo($riesgo)
    {
        $factor = $this->calcularFOcurrencia($riesgo);
        if ($factor >= 80) return 'MUY ALTO';
        if ($factor >= 60) return 'ALTO';
        if ($factor >= 40) return 'NORMAL';
        return 'BAJO';
    }

    public function colorRiesgo($riesgo)
    {
        $factor = $this->calcularFOcurrencia($riesgo);
        if ($factor >= 80) return 'red';
        if ($factor >= 60) return 'yellow';
        if ($factor >= 40) return '#00B050';
        return '#92D050';
    }

    public function colorTexto($riesgo)
    {
        $factor = $this->calcularFOcurrencia($riesgo);
        return ($factor >= 60) ? 'black' : 'white';
    }

    public int $valor = 1;
    public int $min = 1;
    public int $max = 5;

    public function updatedRiesgos($value, $name)
    {
        $parts = explode('.', $name);
        if (count($parts) === 3) {
            [$prefix, $index, $field] = $parts;
            if (in_array($field, ['f','s','p','e','pb','if'])) {
                $this->riesgos[$index][$field] = max(1, min(5, (int)$value));
            }
        }
    }
    #[On('refreshChart')]
    public function refreshChart()
    {
        $this->dispatch('$refresh'); // 🔄 fuerza el re-render del componente
    }

    public function recalcularRiesgosFila($index)
{
    if (!isset($this->riesgos[$index])) return;

    $fila = &$this->riesgos[$index];
    $campos = ['impacto_f','impacto_o','extension_d','probabilidad_m','impacto_fin'];

    // Si faltan campos, no hacemos nada todavía
    foreach ($campos as $campo) {
        if (empty($fila[$campo])) {
            return; // espera a que el usuario complete todos los valores
        }
    }

    // Calcular la fila normalmente
    $this->calcularFila($index);

    // 🚀 Solo cuando la fila está completa, generamos la gráfica
    $this->dispatch('actualizarGrafica', [
        'riesgos' => collect($this->riesgos)->map(fn($r) => [
            'no' => $r['no'] ?? '',
            'riesgo' => $r['riesgo'] ?? '',
            'factor_oc' => (float)($r['factor_oc'] ?? 0),
        ])->values()
    ]);
    }
    public function calcularFila($index)
    {
        if (!isset($this->riesgos[$index])) return;

        $fila = &$this->riesgos[$index];
        $campos = ['impacto_f', 'impacto_o', 'extension_d', 'probabilidad_m', 'impacto_fin'];

        // Validar que estén dentro del rango
        foreach ($campos as $campo) {
            if (!isset($fila[$campo]) || !is_numeric($fila[$campo]) || $fila[$campo] < 1 || $fila[$campo] > 5) {
                $fila[$campo] = null;
            } else {
                $fila[$campo] = (int)$fila[$campo];
            }
        }

        // Calcular Calificación total
        $total = array_sum(array_map(fn($campo) => $fila[$campo] ?? 0, $campos));
        $fila['cal'] = $total;

        // Calcular Porcentaje (sobre 25)
        $porcentaje = $total > 0 ? round(($total / 25) * 100) : 0;
        $fila['factor_oc'] = $porcentaje;

        // Determinar Clase de Riesgo según la Calificación
        if ($total >= 21) {
            $fila['clase_riesgo'] = 'MUY ALTO';
        } elseif ($total >= 16) {
            $fila['clase_riesgo'] = 'ALTO';
        } elseif ($total >= 11) {
            $fila['clase_riesgo'] = 'MEDIO';
        } elseif ($total >= 1) {
            $fila['clase_riesgo'] = 'BAJO';
        } else {
            $fila['clase_riesgo'] = '';
        }

    $this->dispatch('actualizarGrafica', [
        'riesgos' => collect($this->riesgos)->map(fn($r) => [
            'no' => $r['no'] ?? '',
            'riesgo' => $r['riesgo'] ?? '',
            'factor_oc' => (float)($r['factor_oc'] ?? 0),
        ])->values()
    ]);
    }

    public function guardarRiesgos()
{
    if (!$this->content) return;

    foreach ($this->riesgos as $r) {
        // Ignora filas vacías
        if (empty($r['riesgo'])) continue;

        \App\Models\AnalysisDiagram::updateOrCreate(
            ['id' => $r['id'] ?? null],
            [
                'content_id' => $this->content->id,
                'no' => $r['no'],
                'riesgo' => $r['riesgo'],
                'impacto_f' => (int)($r['impacto_f'] ?? 1),
                'impacto_o' => (int)($r['impacto_o'] ?? 1),
                'extension_d' => (int)($r['extension_d'] ?? 1),
                'probabilidad_m' => (int)($r['probabilidad_m'] ?? 1),
                'impacto_fin' => (int)($r['impacto_fin'] ?? 1),
                'cal' => (int)($r['cal'] ?? 0),
                'clase_riesgo' => $r['clase_riesgo'] ?? '',
                'factor_oc' => $r['factor_oc'] ?? 0,
                'orden' => $r['orden'] ?? 1,
                'updated_at' => now(),
            ]
        );
    }

}

public $nuevoNodo = '';
public $colorNodo = '#FFD700';
public $colorLetra = '#111111';
public $tamanoLetra = 14;
public $nodoDesde = '';
public $nodoHasta = '';

public function guardarMapaMental()
{
    $mentalMap = \App\Models\MentalMap::where('content_id', $this->content->id)->first();

    $data = [
        'nodos'              => $this->nodos,
        'relaciones'         => $this->relaciones,
        'background_opacity' => $this->background_opacity,
    ];

    // 🔹 Si se subió un nuevo fondo base64
    if (!empty($this->background_image) && str_starts_with($this->background_image, 'data:image')) {
        $fileName = 'fondo_' . $this->content->id . '_' . time() . '.png';
        $path = storage_path('app/public/mental_maps/' . $fileName);

        // Guardar el base64 como imagen física
        $imageData = explode(',', $this->background_image)[1] ?? null;
        if ($imageData) {
            file_put_contents($path, base64_decode($imageData));
            $data['background_image'] = 'storage/mental_maps/' . $fileName;
            logger('🧩 Fondo guardado en archivo', ['path' => $data['background_image']]);
        }
    } 
    // 🔹 Si no hay nueva imagen, conservar la existente
    elseif ($mentalMap && $mentalMap->background_image) {
        $data['background_image'] = $mentalMap->background_image;
        logger('🧩 Conservando fondo anterior', ['path' => $data['background_image']]);
    }

    \App\Models\MentalMap::updateOrCreate(
        ['content_id' => $this->content->id],
        $data
    );

    session()->flash('success', '🧠 Mapa mental guardado correctamente.');
}
// base64 del fondo
    public $backgroundOpacity = 0.4;
    /** ===== Helpers ===== */

    private function wrapLabelWithoutBreakingWords(string $text, int $width = 12): string
    {
        if (str_contains($text, ' ')) {
            return wordwrap($text, $width, "\n", false);
        }
        return $text;
    }

    private function buildNodeArray(array $base): array
    {
        $label = $this->wrapLabelWithoutBreakingWords((string)($base['label'] ?? ''));

        return [
            'id'    => (int) $base['id'],
            'label' => $label,
            'shape' => 'circle',
            'size'  => 40,
            'color' => $base['color'] ?? '#FFD700',
            'font'  => [
                'size'    => (int) ($base['font_size'] ?? $this->tamanoLetra),
                'face'    => 'Arial',
                'color'   => $base['font_color'] ?? $this->colorLetra,
                'vadjust' => 0,
            ],
            'x' => $base['x'] ?? null,
            'y' => $base['y'] ?? null,
        ];
    }

    private function findNodeIndexById(int $id): ?int
    {
        foreach ($this->nodos as $i => $n) {
            if ((int)($n['id'] ?? 0) === $id) return $i;
        }
        return null;
    }

    /** ===== Acciones ===== */

    public function agregarNodoDesdeFront()
    {
        $nombre = trim($this->nuevoNodo);
        if ($nombre === '') return;

        $maxId = 0;
        foreach ($this->nodos as $n) {
            $maxId = max($maxId, (int)($n['id'] ?? 0));
        }
        $nuevoId = $maxId + 1;

        $node = $this->buildNodeArray([
            'id'         => $nuevoId,
            'label'      => $nombre,
            'color'      => $this->colorNodo,
            'font_color' => $this->colorLetra,
            'font_size'  => $this->tamanoLetra,
        ]);

        $this->nodos[] = $node;
        $this->nuevoNodo = '';

        $this->dispatch('actualizarMapa', nodos: $this->nodos, relaciones: $this->relaciones);
    }

    public function conectarNodos()
    {
        $from = (int) $this->nodoDesde;
        $to   = (int) $this->nodoHasta;

        if (!$from || !$to || $from === $to) return;

        foreach ($this->relaciones as $r) {
            if ((int)$r['from'] === $from && (int)$r['to'] === $to) return;
        }

        $this->relaciones[] = ['from' => $from, 'to' => $to];
        $this->dispatch('actualizarMapa', nodos: $this->nodos, relaciones: $this->relaciones);
    }

    public function actualizarNodo(int $id, string $nuevoNombre)
    {
        $idx = $this->findNodeIndexById($id);
        if ($idx === null) return;

        $actual = $this->nodos[$idx];
        $node = $this->buildNodeArray([
            'id'         => $id,
            'label'      => $nuevoNombre,
            'color'      => $actual['color'] ?? '#FFD700',
            'font_color' => $actual['font']['color'] ?? '#111',
            'font_size'  => $actual['font']['size'] ?? 14,
            'x'          => $actual['x'] ?? null,
            'y'          => $actual['y'] ?? null,
        ]);

        $this->nodos[$idx] = $node;
        $this->dispatch('actualizarMapa', nodos: $this->nodos, relaciones: $this->relaciones);
    }

    public function actualizarColorNodo(int $id, string $nuevoColor)
    {
        $idx = $this->findNodeIndexById($id);
        if ($idx === null) return;

        $actual = $this->nodos[$idx];
        $actual['color'] = $nuevoColor;
        $this->nodos[$idx] = $this->buildNodeArray($actual);

        $this->dispatch('actualizarMapa', nodos: $this->nodos, relaciones: $this->relaciones);
    }

    public function actualizarPosicionNodo(int $id, float $x, float $y)
    {
        $idx = $this->findNodeIndexById($id);
        if ($idx === null) return;

        $this->nodos[$idx]['x'] = $x;
        $this->nodos[$idx]['y'] = $y;
    }

    public function eliminarNodo(int $id)
    {
        // 🔹 Elimina el nodo
        $this->nodos = array_filter($this->nodos, fn($n) => (int)$n['id'] !== $id);

        // 🔹 Elimina también relaciones que lo usen
        $this->relaciones = array_filter($this->relaciones, function ($r) use ($id) {
            return (int)$r['from'] !== $id && (int)$r['to'] !== $id;
        });

        // 🔁 Actualiza el mapa
        $this->dispatch('actualizarMapa', nodos: $this->nodos, relaciones: $this->relaciones);
    }
    // 🔹 Eliminar nodo seleccionado
    public function eliminarNodoSeleccionado(int $id)
    {
        // 🔹 Eliminar solo el nodo con el id indicado
        $this->nodos = array_values(array_filter($this->nodos, function ($n) use ($id) {
            return (int)$n['id'] !== $id;
        }));

        // 🔹 Eliminar solo las relaciones relacionadas a ese nodo
        $this->relaciones = array_values(array_filter($this->relaciones, function ($r) use ($id) {
            return (int)$r['from'] !== $id && (int)$r['to'] !== $id;
        }));

        // 🔁 Reenviar el mapa actualizado a JS
        $this->dispatch('actualizarMapa', nodos: $this->nodos, relaciones: $this->relaciones);
    }

    // 🔹 Eliminar conexión seleccionada
    public function eliminarRelacionSeleccionada($from, $to)
    {
        // 🔹 Solo elimina la relación exacta entre esos dos nodos
        $this->relaciones = array_values(array_filter($this->relaciones, function ($r) use ($from, $to) {
            return !((int)$r['from'] === (int)$from && (int)$r['to'] === (int)$to);
        }));

        // 🔁 Actualiza el mapa sin tocar los demás enlaces
        $this->dispatch('actualizarMapa', nodos: $this->nodos, relaciones: $this->relaciones);
    }
#[On('setBackground')]
public function setBackground($base64)
{
    // 🧹 Si viene vacío, elimina el fondo actual
    if (empty($base64)) {
        if ($this->content) {
            \App\Models\MentalMap::where('content_id', $this->content->id)
                ->update(['background_image' => null]);
        }

        $this->backgroundImage  = null;
        logger('🧹 Fondo eliminado correctamente');
        return;
    }

    // 🧩 Si viene un nuevo fondo base64 (string completo)
    if (is_array($base64) && isset($base64['base64'])) {
        $base64 = $base64['base64'];
    }

    if (!is_string($base64) || strpos($base64, 'data:image') === false) {
        logger('⚠️ Formato inválido de fondo recibido');
        return;
    }

    // ✅ Guardar directamente el string base64 completo en la base de datos
    if ($this->content) {
        \App\Models\MentalMap::updateOrCreate(
            ['content_id' => $this->content->id],
            ['background_image' => $base64]
        );
    }

    $this->backgroundImage  = $base64;

    logger('✅ Fondo guardado como base64 en BD', [
        'len' => strlen($base64),
        'inicio' => substr($base64, 0, 40),
    ]);
}
public function getNextReferenceNumber($reportId)
{
    return \App\Models\ContentReference::nextNumberForReport($reportId);
}
public function renumerarReferenciasReporte($reportId)
{
    $refs = \App\Models\ContentReference::renumerarPorReporte($reportId);
    return $refs->map(fn($r) => [
        'num' => $r->numero,
        'texto' => $r->texto,
    ]);
}

public function eliminarYRenumerarReferencia(int $reportId, int $numero)
{
    $map = [];
    $refEliminada = false;

    DB::transaction(function () use ($reportId, $numero, &$map, &$refEliminada) {

        // 1️⃣ Eliminar la referencia específica
        $ref = \App\Models\ContentReference::query()
            ->where('numero', $numero)
            ->whereHas('content', function ($q) use ($reportId) {
                $q->whereHas('reportTitle', fn($r) => $r->where('report_id', $reportId))
                  ->orWhereHas('reportTitleSubtitle.reportTitle', fn($r) => $r->where('report_id', $reportId))
                  ->orWhereHas('reportTitleSubtitleSection.reportTitleSubtitle.reportTitle', fn($r) => $r->where('report_id', $reportId));
            })
            ->first();

        if ($ref) {
            $ref->delete();
            $refEliminada = true;
        }

        if (! $refEliminada) return;

        // 2️⃣ Buscar todas las referencias posteriores y restarles 1
        $refsPosteriores = \App\Models\ContentReference::query()
            ->whereHas('content', function ($q) use ($reportId) {
                $q->whereHas('reportTitle', fn($r) => $r->where('report_id', $reportId))
                  ->orWhereHas('reportTitleSubtitle.reportTitle', fn($r) => $r->where('report_id', $reportId))
                  ->orWhereHas('reportTitleSubtitleSection.reportTitleSubtitle.reportTitle', fn($r) => $r->where('report_id', $reportId));
            })
            ->where('numero', '>', $numero)
            ->orderBy('numero')
            ->get();

        foreach ($refsPosteriores as $r) {
            $old = (int)$r->numero;
            $new = $old - 1;
            $map[$old] = $new;
            $r->numero = $new;
            $r->save();
        }

        // 3️⃣ Reescribir todos los contenidos
        $contenidos = \App\Models\Content::query()
            ->whereHas('reportTitle', fn($r) => $r->where('report_id', $reportId))
            ->orWhereHas('reportTitleSubtitle.reportTitle', fn($r) => $r->where('report_id', $reportId))
            ->orWhereHas('reportTitleSubtitleSection.reportTitleSubtitle.reportTitle', fn($r) => $r->where('report_id', $reportId))
            ->get();

        foreach ($contenidos as $c) {
            if (! str_contains($c->cont ?? '', '<sup')) continue;

            $nuevo = $this->renumerarHtmlSup($c->cont ?? '', $numero);
            if ($nuevo !== $c->cont) {
                $c->cont = $nuevo;
                $c->save();
            }
        }

        // 4️⃣ Agregar el eliminado al mapa
        $map[$numero] = null;
    });

    // 5️⃣ Devolver referencias actualizadas
    $refsActuales = \App\Models\ContentReference::query()
        ->whereHas('content', function ($q) use ($reportId) {
            $q->whereHas('reportTitle', fn($r) => $r->where('report_id', $reportId))
              ->orWhereHas('reportTitleSubtitle.reportTitle', fn($r) => $r->where('report_id', $reportId))
              ->orWhereHas('reportTitleSubtitleSection.reportTitleSubtitle.reportTitle', fn($r) => $r->where('report_id', $reportId));
        })
        ->orderBy('numero')
        ->get()
        ->map(fn($r) => ['num' => (int)$r->numero, 'texto' => $r->texto])
        ->toArray();

    $this->referencias = $refsActuales;

    return [
        'map' => $map,
        'referencias' => $refsActuales
    ];
}

/**
 * Reescribe los <sup>[n]</sup> dentro del contenido HTML.
 * - Si n == $numeroEliminado → elimina el <sup>.
 * - Si n > $numeroEliminado → lo reduce en 1.
 */
private function renumerarHtmlSup(string $html, int $numeroEliminado): string
{
    if (trim($html) === '') return $html;

    // Busca coincidencias como <sup ...>[12]</sup> (sin importar estilos)
    $pat = '/<sup[^>]*>\s*\[(\d+)\]\s*<\/sup>/i';

    $nuevo = preg_replace_callback($pat, function ($m) use ($numeroEliminado) {
        $num = (int)$m[1];
        if ($num === $numeroEliminado) {
            // Eliminar este superíndice completo
            return '';
        } elseif ($num > $numeroEliminado) {
            $nuevoNum = $num - 1;
            // Sustituir el número dentro del [ ]
            return str_replace('['.$num.']', '['.$nuevoNum.']', $m[0]);
        }
        return $m[0];
    }, $html);

    return $nuevo ?? $html;
}

/**
 * Reescribe todos los <span class="ref" data-num="X"><sup>[X]</sup></span>
 * Si X == $numeroEliminado → elimina el span.
 * Si X > $numeroEliminado → resta 1 a X.
 */
private function renumerarHtmlReduciendo(string $html, int $numeroEliminado): string
{
    if (trim($html) === '') return $html;

    $pat = '/<span\s+class=["\']ref["\']([^>]*)data-num=["\'](\d+)["\']([^>]*)>\s*<sup>\[(\d+)\]<\/sup>\s*<\/span>/i';

    $nuevo = preg_replace_callback($pat, function ($m) use ($numeroEliminado) {
        $num = (int)$m[2];
        if ($num === $numeroEliminado) {
            // Eliminar este span
            return '';
        } elseif ($num > $numeroEliminado) {
            $nuevoNum = $num - 1;
            $reemplazo = preg_replace('/data-num=["\']'.$num.'["\']/', 'data-num="'.$nuevoNum.'"', $m[0]);
            $reemplazo = preg_replace('/\['.$num.'\]/', '['.$nuevoNum.']', $reemplazo);
            return $reemplazo;
        }
        return $m[0];
    }, $html);

    return $nuevo ?? $html;
}

/**
 * Reescribe todos los <span class="ref" data-num="X"><sup>[X]</sup></span> según $map.
 * Si el map[X] = null → elimina el span.
 */
private function renumerarHtmlConMapa(string $html, array $map): string
{
    if ($html === '' || empty($map)) return $html;

    // Reemplazo seguro con callback
    $pat = '/<span\s+class=["\']ref["\']([^>]*)data-num=["\'](\d+)["\']([^>]*)>\s*<sup>\[(\d+)\]<\/sup>\s*<\/span>/i';

    $nuevo = preg_replace_callback($pat, function ($m) use ($map) {
        $oldAttrNum = (int)$m[2]; // data-num
        // $m[4] es el número dentro del <sup>[X]</sup> (lo validamos también)
        if (!array_key_exists($oldAttrNum, $map)) {
            // No hay cambio para este número
            return $m[0];
        }
        $nuevoNum = $map[$oldAttrNum];
        if ($nuevoNum === null) {
            // Se elimina el span completo
            return '';
        }
        // Reescribir el data-num y el <sup>[X]</sup>
        $reemplazo = $m[0];
        // data-num="old" -> data-num="new"
        $reemplazo = preg_replace('/data-num=["\']'.$oldAttrNum.'["\']/', 'data-num="'.$nuevoNum.'"', $reemplazo);
        // [old] -> [new]
        $reemplazo = preg_replace('/\['.$oldAttrNum.'\]/', '['.$nuevoNum.']', $reemplazo);

        return $reemplazo;
    }, $html);

    return $nuevo ?? $html;
}
public function insertarReferenciaIntermedia(int $reportId, int $contentId, string $texto = '')
{
    $contenidoActual = \App\Models\Content::find($contentId);
    if (!$contenidoActual) {
        throw new \Exception("Contenido no encontrado.");
    }

    // 🔹 Obtener todos los contenidos del reporte en orden
    $contenidos = \App\Models\Content::query()
        ->whereHas('reportTitle', fn($r) => $r->where('report_id', $reportId))
        ->orWhereHas('reportTitleSubtitle.reportTitle', fn($r) => $r->where('report_id', $reportId))
        ->orWhereHas('reportTitleSubtitleSection.reportTitleSubtitle.reportTitle', fn($r) => $r->where('report_id', $reportId))
        ->orderBy('id')
        ->get();

    $orden = $contenidos->pluck('id')->search($contentId);
    if ($orden === false) {
        throw new \Exception("El contenido no pertenece al reporte.");
    }

    // 🔹 Determinar el último número dentro de este contenido
    $maxLocal = \App\Models\ContentReference::where('content_id', $contentId)->max('numero') ?? 0;
    $nuevoNum = $maxLocal + 1;

    // 🔹 Recorre todas las referencias posteriores (+1)
    $refsPosteriores = \App\Models\ContentReference::query()
        ->whereHas('content', function ($q) use ($contenidos, $orden) {
            $q->whereIn('id', $contenidos->slice($orden + 1)->pluck('id'));
        })
        ->orderBy('numero', 'desc')
        ->get();

    foreach ($refsPosteriores as $r) {
        $r->numero = $r->numero + 1;
        $r->save();
    }

    // 🔹 Actualiza los contenidos posteriores
    $this->incrementarSupEnContenidos($contenidos->slice($orden + 1), $nuevoNum);

    // ✅ Crear la nueva referencia en BD directamente
    if ($texto !== '') {
        \App\Models\ContentReference::create([
            'content_id' => $contentId,
            'numero'     => $nuevoNum,
            'texto'      => $texto,
        ]);
    }

    return $nuevoNum;
}

/**
 * 🔁 Incrementa en +1 los números de <sup>[n]</sup> >= $desde en una colección de contenidos
 */
private function incrementarSupEnContenidos($contenidos, int $desde)
{
    foreach ($contenidos as $c) {
        if (! str_contains($c->cont ?? '', '<sup')) continue;

        $nuevo = preg_replace_callback(
            '/<sup[^>]*>\s*\[(\d+)\]\s*<\/sup>/i',
            function ($m) use ($desde) {
                $num = (int)$m[1];
                if ($num >= $desde) {
                    $nuevoNum = $num + 1;
                    return str_replace('['.$num.']', '['.$nuevoNum.']', $m[0]);
                }
                return $m[0];
            },
            $c->cont
        );

        if ($nuevo !== $c->cont) {
            $c->cont = $nuevo;
            $c->save();
        }
    }
}




    public function render()
    {
        return view('livewire.reports.botones-add.editc', [
            'RTitle' => $this->RTitle,
            'RSubtitle' => $this->RSubtitle,
            'RSection' => $this->RSection,
            'boton'  => $this->boton,
            'rp'  => $this->rp,
            'nodos '=> $this->nodos,
        ]);
    }
}
