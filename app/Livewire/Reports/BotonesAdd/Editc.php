<?php

namespace App\Livewire\Reports\BotonesAdd;

use App\Models\AnalysisDiagram;
use Livewire\Component;
use App\Models\Content;
use Livewire\WithFileUploads;
use App\Models\ReportTitle;
use App\Models\ReportTitleSubtitle;
use App\Models\ReportTitleSubtitleSection;
use App\Models\Report;

use Livewire\Attributes\On;

class Editc extends Component
{
    use WithFileUploads;
    public $RTitle;
    public $RSubtitle;
    public $RSection;
    public $content;
    public $contenido;
    public $img1;
    public $img2;
    public $img3;
    public $leyenda1;
    public $leyenda2;
    public $leyenda3;
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

    public function mount($id,$boton,$rp)
    {
    $report = Report::findOrFail($rp);
    $this->authorize('update', $report); 

    $this->rep = Report::findOrFail($rp);
    $this->authorize('update', $report); // 👈 ahora sí se evalúa la policy
        if($boton == 'tit'){
            $this->content = Content::where('r_t_id', $id)->first();
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
            $this->content = Content::where('r_t_s_id', $id)->first();
            if ($this->content) {
                $this->riesgos = AnalysisDiagram::where('content_id',  $this->content->id)
                    ->orderBy('orden')
                    ->get()
                    ->map(function ($r) {
                        return [
                            'id'      => $r->id,
                            'no'      => $r->no,
                            'riesgo'  => $r->riesgo,
                            'f'       => $r->f,
                            's'       => $r->s,
                            'p'       => $r->p,
                            'e'       => $r->e,
                            'pb'      => $r->pb,
                            'if'      => $r->if,
                        ];
                    })
                    ->toArray();
            } else {
                $this->riesgos = [];
            }
            $this->rep->titles = ReportTitle::where('report_id', $this->rep->id)->where('status',1)->get();
            // Cargamos valores existentes
            foreach ($this->rep->titles as $title) 
            {
            if( ReportTitleSubtitle::where('r_t_id', $title->id)->where('subtitle_id', 14)->exists()){
            $mosler = ReportTitleSubtitle::where('r_t_id', $title->id)->where('subtitle_id', 14)->first();  
                if( Content::where('r_t_s_id',$mosler->id)->exists()){
                    $c = Content::where('r_t_s_id',$mosler->id)->first();
                    $this->risks = AnalysisDiagram::where('content_id',$c->id)->get();
                }

             }
            }
            $this->cargarRiesgos();
            
           
            $this->contenido = $this->content->cont;
            $this->leyenda1 = $this->content->leyenda1;
            $this->leyenda2 = $this->content->leyenda2;
            $this->leyenda3 = $this->content->leyenda3;

            $this->oldImg1 = $this->content->img1;
            $this->oldImg2 = $this->content->img2;
            $this->oldImg3 = $this->content->img3;
            $this->RSubtitle = ReportTitleSubtitle::findOrFail($id);
        } else if($boton == 'sec'){
            $this->RTitle = null;
            $this->RSubtitle = null;
            $this->content = Content::where('r_t_s_s_id', $id)->first();
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

        $this->content->update([
            'cont'     => $this->contenido,
            'img1'     => $path1,
            'leyenda1' => $this->leyenda1,
            'img2'     => $path2,
            'leyenda2' => $this->leyenda2,
            'img3'     => $path3,
            'leyenda3' => $this->leyenda3,
        ]);
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

    public function render()
    {
        return view('livewire.reports.botones-add.editc', [
            'RTitle' => $this->RTitle,
            'RSubtitle' => $this->RSubtitle,
            'RSection' => $this->RSection,
            'boton'  => $this->boton,
            'rp'  => $this->rp,
        ]);
    }
}
