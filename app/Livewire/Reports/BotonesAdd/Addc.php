<?php

namespace App\Livewire\Reports\BotonesAdd;

use App\Models\AccionSeguridad;
use Livewire\Component;
use Livewire\WithFileUploads;

use App\Models\AnalysisDiagram;
use App\Models\Subtitle;
use App\Models\Report;
use App\Models\Content;
use App\Models\Foda;
use App\Models\ReportTitle;
use App\Models\ReportTitleSubtitle;
use App\Models\ReportTitleSubtitleSection;
use App\Models\MentalMap;
use App\Models\OrganigramaControl;
use Livewire\Attributes\On;

class Addc extends Component
{
    use WithFileUploads;

    public $RTitle;
    public $RSubtitle;
    public $RSection;
    public $boton;
    public $contenido;
    public $contenido_m_p_a;
    public $contenido_a_p;
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

    public $informacion = [];
    public $riesgos = [];
    public $rep;

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
    public $acciones;

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

        $this->acciones = [
                'SEGURIDAD FÍSICA' => [
                    ['no' => 1, 'tema' => 'Protección Perimetral', 'accion' => null, 't_costo' => null, 'nivel_p' => null],
                    ['no' => 2, 'tema' => 'Manual de Operaciones de Seguridad Escolar', 'accion' => null, 't_costo' => null, 'nivel_p' => null],
                    ['no' => 3, 'tema' => 'Rondines internos y perimetrales', 'accion' => null, 't_costo' => null, 'nivel_p' => null],
                    ['no' => 4, 'tema' => 'Instalación de señalizaciones diversas', 'accion' => null, 't_costo' => null, 'nivel_p' => null],
                ],
                'TECNOLOGÍA DE SEGURIDAD' => [
                    ['no' => 5, 'tema' => 'Controles de Acceso', 'accion' => null, 't_costo' => null, 'nivel_p' => null],
                    ['no' => 6, 'tema' => 'Sistemas de revisiones', 'accion' => null, 't_costo' => null, 'nivel_p' => null],
                    ['no' => 7, 'tema' => 'Centro de Monitoreo de CCTV', 'accion' => null, 't_costo' => null, 'nivel_p' => null],
                    ['no' => 8, 'tema' => 'Cámaras de CCTV', 'accion' => null, 't_costo' => null, 'nivel_p' => null],
                    ['no' => 9, 'tema' => 'Mapeo de las cámaras de CCTV', 'accion' => null, 't_costo' => null, 'nivel_p' => null],
                    ['no' => 10, 'tema' => 'SITE / IT', 'accion' => null, 't_costo' => null, 'nivel_p' => null],
                    ['no' => 11, 'tema' => 'Tour de Guardias', 'accion' => null, 't_costo' => null, 'nivel_p' => null],
                    ['no' => 12, 'tema' => 'Sistema de Alarmas de Seguridad', 'accion' => null, 't_costo' => null, 'nivel_p' => null],
                    ['no' => 13, 'tema' => 'Radios de comunicación interna', 'accion' => null, 't_costo' => null, 'nivel_p' => null],
                ],
                'GUARDIAS DE SEGURIDAD' => [
                    ['no' => 14, 'tema' => 'Entrevistas del personal', 'accion' => null, 't_costo' => null, 'nivel_p' => null],
                    ['no' => 15, 'tema' => 'Verificación de perfiles', 'accion' => null, 't_costo' => null, 'nivel_p' => null],
                    ['no' => 16, 'tema' => 'Incremento de elementos asignados', 'accion' => null, 't_costo' => null, 'nivel_p' => null],
                ],
                'CAPACITACIÓN' => [
                    ['no' => 17, 'tema' => 'Capacitación del personal', 'accion' => null, 't_costo' => null, 'nivel_p' => null],
                ],
                'PROCEDIMIENTOS DIVERSOS' => [
                    ['no' => 18, 'tema' => 'Manejo de materiales peligrosos', 'accion' => null, 't_costo' => null, 'nivel_p' => null],
                    ['no' => 19, 'tema' => 'Cuarto eléctrico', 'accion' => null, 't_costo' => null, 'nivel_p' => null],
                ],
                'PROGRAMAS PREVENTIVOS' => [
                    ['no' => 20, 'tema' => 'Mantenimiento de SITE / IT', 'accion' => null, 't_costo' => null, 'nivel_p' => null],
                    ['no' => 21, 'tema' => 'SITE – Instalación de sistema vs incendios', 'accion' => null, 't_costo' => null, 'nivel_p' => null],
                    ['no' => 22, 'tema' => 'Poda de árboles y vegetación', 'accion' => null, 't_costo' => null, 'nivel_p' => null],
                    ['no' => 23, 'tema' => 'Control de llaves', 'accion' => null, 't_costo' => null, 'nivel_p' => null],
                    ['no' => 24, 'tema' => 'Programa de mantenimiento del Auditorio', 'accion' => null, 't_costo' => null, 'nivel_p' => null],
                    ['no' => 25, 'tema' => 'Programa de mantenimiento preventivo de iluminación', 'accion' => null, 't_costo' => null, 'nivel_p' => null],
                ],
                'OTROS DIVERSOS' => [
                    ['no' => 26, 'tema' => 'Comité de Gestión de Riesgos Institucionales', 'accion' => null, 't_costo' => null, 'nivel_p' => null],
                    ['no' => 27, 'tema' => 'Asesoría y coordinación permanente de Seguridad por parte de la SSP', 'accion' => null, 't_costo' => null, 'nivel_p' => null],
                    ['no' => 28, 'tema' => 'Puerta de acceso al estacionamiento de la instalación', 'accion' => null, 't_costo' => null, 'nivel_p' => null],
                ],
            ];
            
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
            if ($nl->subtitle_id === 32 || $nl->subtitle_id === 16 ) {
                $data['grafica'] = $this->grafica;
            }            
            if ($nl->subtitle_id === 17 ) {
                $data['contenido_a_p'] = $this->contenido_a_p;
                $data['contenido_m_p_a'] = $this->contenido_m_p_a;
            }if ($nl->subtitle_id === 42 ) {
                $data['puesto_r'] = $this->puesto_r;
                $data['nombre_r'] = $this->nombre_r;
                $data['puesto_e'] = $this->puesto_e;
                $data['nombre_e'] = $this->nombre_e;
                $data['puesto_c'] = $this->puesto_c;
                $data['nombre_c'] = $this->nombre_c;
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
                if (!empty($this->nodos) || !empty($this->relaciones) || $this->backgroundImage) {
                    $mapa = MentalMap::firstOrNew(['content_id' => $content->id]);
                    $mapa->nodos = $this->nodos;
                    $mapa->relaciones = $this->relaciones;
                    $mapa->background_image = $this->backgroundImage;
                    $mapa->background_opacity = $this->backgroundOpacity;
                    $mapa->save();
                }

            }
            if ($name == 18) {
                foreach ($this->riesgos as $i => $riesgo) {
                    // Tomar medidas y acciones si existen, si no, dejar como null
                    $medidas = $this->informacion[$i]['medidas_p'] ?? null;
                    $acciones = $this->informacion[$i]['acciones_planes'] ?? null;

                    OrganigramaControl::create([
                        'content_id'       => $content->id,
                        'no'               => $riesgo['no'],
                        'riesgo'           => $riesgo['riesgo'],
                        'medidas_p'        => $medidas,
                        'acciones_planes'  => $acciones,
                    ]);
                }
            }
            if ($name == 33) {
                Foda::create([
                    'content_id'    =>  $content->id,
                    'fortalezas'    =>  $this->fortalezas,
                    'debilidades'   =>  $this->debilidades,
                    'oportunidades' =>  $this->oportunidades,
                    'amenazas'       =>  $this->amenazas,
                ]);
            }
            if ($name == 38) {
                $now = now();
                    $rows = [];

                    foreach ($this->acciones as $seccion => $temas) {
                        foreach ($temas as $r) {
                            $rows[] = [
                                'content_id' => $content->id,
                                'no'         => $r['no'],
                                'tit'    => $seccion,
                                'tema'       => $r['tema'],
                                'accion'     => $r['accion'] ?? null,
                                't_costo'    => $r['t_costo'] ?? null,
                                'nivel_p'    => $r['nivel_p'] ?? null,
                                'created_at' => $now,
                                'updated_at' => $now,
                            ];
                        }
                    }

                    if (!empty($rows)) {
                        \App\Models\AccionSeguridad::insert($rows);
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
    $fila['factor_oc'] = $total > 0 ? round(($total/25)*100) : '0';

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

    public $nodos = [];
    public $relaciones = [];
    public $nuevoNodo = '';
    public $colorNodo = '#FFD700';
    public $colorLetra = '#111111';
    public $tamanoLetra = 14;
    public $nodoDesde = '';
    public $nodoHasta = '';
    public $backgroundImage;   // base64 del fondo
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
        $this->backgroundImage = $base64;
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
