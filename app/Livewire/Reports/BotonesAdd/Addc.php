<?php

namespace App\Livewire\Reports\BotonesAdd;

use Livewire\Component;
use Livewire\WithFileUploads;

use App\Models\AnalysisDiagram;
use App\Models\Subtitle;
use App\Models\Report;
use App\Models\Content;
use App\Models\ReportTitle;
use App\Models\ReportTitleSubtitle;
use App\Models\ReportTitleSubtitleSection;

use Livewire\Attributes\On;

class Addc extends Component
{
    use WithFileUploads;

    public $RTitle;
    public $RSubtitle;
    public $RSection;
    public $boton;
    public $contenido;
    public $report;
    public $rp;
    public $img1;
    public $img2;
    public $img3;
    public $leyenda1;
    public $leyenda2;
    public $leyenda3;
    public $path;
    public $path2;
    public $path3;
    public $mosler;
    public $grafica='bar';
    public $c;
    public $risks;

    public $que;
    public $como;
    public $quien;
    public $por_que;
    public $donde;
    public $cuanto;
    public $de;
    public $hasta;

    public $ti;
    public $su;

    public $riesgos = [];
    public $rep;
    public function mount($id, $boton, $rp)
    {
        $report = Report::findOrFail($rp);
        $this->authorize('update', $report); 

        $this->rep = Report::findOrFail($rp);
        if($boton == 'tit'){
            $this->RTitle = ReportTitle::findOrFail($id);
            $this->RSubtitle = null;
        } elseif($boton == 'sub'){
            $ti =ReportTitle::where('report_id', $this->rep->id)
            ->where('title_id', 5)
            ->where('status',1)
            ->first();
            $this->su = ReportTitleSubtitle::where('r_t_id',$ti->id)
            ->whereBetween('subtitle_id', [20, 29])
            ->where('status',1)
            ->with('subtitle')
            ->get();
            //MATRIZ DE RIESGO
           $this->riesgos = [
            ['no'=>'R01','riesgo'=>'Intrusión'],
            ['no'=>'R02','riesgo'=>'Invasión para ocupación de áreas adyacentes a instalación'],
            ['no'=>'R03','riesgo'=>'Manifestaciones sociales y movimientos sindicales'],
            ['no'=>'R04','riesgo'=>'Ciber ataque – Sistemas de la organización'],
            ['no'=>'R05','riesgo'=>'Filtración de información'],
            ['no'=>'R06','riesgo'=>'Emergencias médicas'],
            ['no'=>'R07','riesgo'=>'Tempestad y/o lluvia con inundaciones alta intensidad'],
            ['no'=>'R08','riesgo'=>'Lesiones'],
            ['no'=>'R09','riesgo'=>'Amenazas a empleados'],
            ['no'=>'R10','riesgo'=>'Incendio'],
            ['no'=>'R11','riesgo'=>'Sismo'],
        ];

        foreach ($this->riesgos as &$r) {
            $r['impacto_f']=null; $r['impacto_o']=null; $r['extension_d']=null;
            $r['probabilidad_m']=null; $r['impacto_fin']=null;
            $r['cal']=0; $r['clase_riesgo']=''; $r['factor_oc']='';
        }
        unset($r);

            //TERMINA MATRIZ DE RIESGO


            
            $this->RSubtitle = ReportTitleSubtitle::findOrFail($id);
            $this->rep->titles = ReportTitle::where('report_id', $this->rep->id)->where('status',1)->get();
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
            
            $this->RTitle = null;
        } else if($boton == 'sec'){
            $this->RTitle = null;
            $this->RSubtitle = null;
            $this->RSection = ReportTitleSubtitleSection::findOrFail($id);

        }
        //$this->addFila();


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
        unset($this->riesgos[$index]);
        $this->riesgos = array_values($this->riesgos);
        $this->renumerar();
    }
    public $nl;
    public function store($id_, $boton, $id)
    {
        $this->validate([
            'img1' => 'nullable|image|required_with:leyenda1',
            'img2' => 'nullable|image|required_with:leyenda2',
            'img3' => 'nullable|image|required_with:leyenda3',
            'leyenda1' => 'nullable|string|required_with:img1',
            'leyenda2' => 'nullable|string|required_with:img2',
            'leyenda3' => 'nullable|string|required_with:img3',
        ], [
            'img1.required_with'     => '⚠️ Si agregas un título, también debes subir una imagen.',
            'leyenda1.required_with' => '⚠️ Si subes una imagen, también debes escribir un título.',
            'img2.required_with'     => '⚠️ Si agregas un título, también debes subir una imagen.',
            'leyenda2.required_with' => '⚠️ Si subes una imagen, también debes escribir un título.',
            'img3.required_with'     => '⚠️ Si agregas un título, también debes subir una imagen.',
            'leyenda3.required_with' => '⚠️ Si subes una imagen, también debes escribir un título.',
        ]);
  
        $path  = $this->img1 ? $this->img1->store('img_cont1', 'public') : null;
        $path2 = $this->img2 ? $this->img2->store('img_cont2', 'public') : null;
        $path3 = $this->img3 ? $this->img3->store('img_cont3', 'public') : null;

        if ($boton == 'tit') {
     
            // 👉 Crear el Content y guardar la instancia
            $content = Content::create([
                'cont'     => $this->contenido,
                'r_t_id'   => $id_,
                'img1'     => $path,
                'img2'     => $path2,
                'img3'     => $path3,
                'leyenda1' => $this->leyenda1,
                'leyenda2' => $this->leyenda2,
                'leyenda3' => $this->leyenda3,
            ]);

            session()->flash('cont', 'Se agrego contenido de Titulo con exito.');
            $this->redirectRoute('my_reports.addcontenido', ['id' => $id], navigate: true);

        } elseif ($boton == 'sub') {
        $nl = ReportTitleSubtitle::findOrFail($id_);
        $name = Subtitle::where('id', $nl->subtitle_id)->value('id');
            $data = [
                'cont'     => $this->contenido,
                'r_t_s_id' => $id_,
                'img1'     => $path,
                'img2'     => $path2,
                'img3'     => $path3,
                'leyenda1' => $this->leyenda1,
                'leyenda2' => $this->leyenda2,
                'leyenda3' => $this->leyenda3,
            ];
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
            // Si el nombre coincide, agrega la clave extra
            if ($nl->subtitle_id === 32) {
                $data['grafica'] = $this->grafica;
            }

            // Finalmente crea el contenido
            $content = Content::create($data);
            if ($name == 14) {
            $rows = [];
            $now = now();
            foreach ($this->riesgos as $index => $r) {
                $rows[] = [
                    'no'           => $r['no'],
                    'riesgo'       => $r['riesgo'],
                    'f'            => (int)$r['f'],
                    's'            => (int)$r['s'],
                    'p'            => (int)$r['p'],
                    'e'            => (int)$r['e'],
                    'pb'           => (int)$r['pb'],
                    'if'           => (int)$r['if'],
                    'f_ocurrencia' => $this->calcularFOcurrencia($r),
                    'content_id'    => $content->id,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                    'orden'        => $index + 1,
                ];
            }
            
            if (!empty($rows)) {
                AnalysisDiagram::insert($rows);
            }
        }
        if ($name == 32) {
            $rows = [];
                $now = now();

                foreach ($this->riesgos as $r) {
                    $r = $this->normalizaYCalcula($r);

                    $rows[] = [
                        'content_id'     => $content->id,
                        'no'             => $r['no'],
                        'riesgo'         => $r['riesgo'],
                        'impacto_f'      => $r['impacto_f'],
                        'impacto_o'      => $r['impacto_o'],
                        'extension_d'    => $r['extension_d'],
                        'probabilidad_m' => $r['probabilidad_m'],
                        'impacto_fin'    => $r['impacto_fin'],
                        'cal'            => $r['cal'],
                        'clase_riesgo'   => $r['clase_riesgo'],
                        'factor_oc'      => $r['factor_oc'],
                        'created_at'     => $now,
                        'updated_at'     => $now,
                    ];
                }
                
                if (!empty($rows)) {
                    AnalysisDiagram::insert($rows);
                }

            }
            session()->flash('cont', 'Se agrego contenido de Subtitulo con exito.');
            $this->redirectRoute('my_reports.addcontenido', ['id' => $id], navigate: true);

        } elseif ($boton == 'sec') {
            $content =Content::create([
                'cont'       => $this->contenido,
                'r_t_s_s_id' => $id_,
                'img1'       => $path,
                'img2'       => $path2,
                'img3'       => $path3,
                'leyenda1'   => $this->leyenda1,
                'leyenda2'   => $this->leyenda2,
                'leyenda3'   => $this->leyenda3,
            ]);

            session()->flash('cont', 'Se agrego contenido de Sección con exito.');
            $this->redirectRoute('my_reports.addcontenido', ['id' => $id], navigate: true);
        }
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

    public function updatedRiesgos2($value, $name)
    {
        $parts = explode('.', $name);
        if (count($parts) === 3) {
            [$prefix, $index, $field] = $parts;
            if (in_array($field, ['f','s','p','e','pb','if'])) {
                $this->riesgos[$index][$field] = max(1, min(5, (int)$value));
            }
        }
    }


    public function updatedRiesgos()
{
    // Emitimos los datos actualizados al front
    $this->dispatch('actualizarGrafica', [
        'riesgos' => collect($this->riesgos)->map(fn($r) => [
            'no' => $r['no'] ?? '',
            'riesgo' => $r['riesgo'] ?? '',
            'factor_oc' => $r['factor_oc'] ?? 0,
        ])->values()
    ]);
}
    //MATRIZ DE RIESGO
   // Escucha cambios en los campos de la tabla de riesgos

   private function normalizaYCalcula(array $fila): array
{
    $campos = ['impacto_f','impacto_o','extension_d','probabilidad_m','impacto_fin'];
    foreach ($campos as $c) {
        $v = $fila[$c] ?? null;
        $fila[$c] = is_numeric($v) ? max(1, min(5, (int)$v)) : null;
    }

    $total = array_sum(array_map(fn($c)=> (int)($fila[$c] ?? 0), $campos));
    $fila['cal'] = $total;
    $fila['factor_oc'] = $total > 0 ? round(($total/25)*100) . '%' : '0%';

    if ($total >= 21)       $fila['clase_riesgo'] = 'MUY ALTO';
    elseif ($total >= 16)   $fila['clase_riesgo'] = 'ALTO';
    elseif ($total >= 11)   $fila['clase_riesgo'] = 'MEDIO';
    elseif ($total >= 1)    $fila['clase_riesgo'] = 'BAJO';
    else                    $fila['clase_riesgo'] = '';

    return $fila;
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


    public function render()
    {
        return view('livewire.reports.botones-add.addc', [
            'RTitle' => $this->RTitle,
            'RSubtitle' => $this->RSubtitle,
            'RSection' => $this->RSection,
            'boton'  => $this->boton,
            'rp'  => $this->rp,
            'risks' => $this->risks,
            'rep' => $this->rep,
            'su' => $this->su,
        ]);

    }


}
