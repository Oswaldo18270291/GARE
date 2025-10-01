<?php

namespace App\Livewire\Reports\BotonesAdd;

use Livewire\Component;
use App\Models\Report;
use App\Models\Content;
use App\Models\ReportTitle;
use App\Models\ReportTitleSubtitle;
use App\Models\ReportTitleSubtitleSection;
use Livewire\WithFileUploads;

use App\Models\AnalysisDiagrams;
use App\Models\Subtitle;

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
    public $riesgos = [];

    public function mount($id, $boton, $rp)
    {
        $report = Report::findOrFail($rp);
        $this->authorize('update', $report); // 👈 ahora sí se evalúa la policy
        $rp = Report::findOrFail($rp);
        if($boton == 'tit'){
            $this->RTitle = ReportTitle::findOrFail($id);
            $this->RSubtitle = null;
        } elseif($boton == 'sub'){
            $this->RSubtitle = ReportTitleSubtitle::findOrFail($id);
            $this->RTitle = null;
        } else if($boton == 'sec'){
            $this->RTitle = null;
            $this->RSubtitle = null;
            $this->RSection = ReportTitleSubtitleSection::findOrFail($id);

        }

                $this->riesgos = [
            ['no' => 'R01', 'riesgo' => 'Intrusión.', 'f' => 1, 's' => 1, 'p' => 1, 'e' => 1, 'pb' => 1, 'if' => 1],
            ['no' => 'R02', 'riesgo' => 'Invasión para ocupación de áreas.', 'f' => 1, 's' => 1, 'p' => 1, 'e' => 1, 'pb' => 1, 'if' => 1],
            ['no' => 'R03', 'riesgo' => 'Manifestaciones sociales y movimientos sindicales.', 'f' => 1, 's' => 1, 'p' => 1, 'e' => 1, 'pb' => 1, 'if' => 1],
            ['no' => 'R04', 'riesgo' => 'Ciber intrusión con captura y bloqueo de datos de la empresa.', 'f' => 1, 's' => 1, 'p' => 1, 'e' => 1, 'pb' => 1, 'if' => 1],
            ['no' => 'R05', 'riesgo' => 'Filtración de información.', 'f' => 1, 's' => 1, 'p' => 1, 'e' => 1, 'pb' => 1, 'if' => 1],
            ['no' => 'R06', 'riesgo' => 'Emergencias médicas.', 'f' => 1, 's' => 1, 'p' => 1, 'e' => 1, 'pb' => 1, 'if' => 1],
            ['no' => 'R07', 'riesgo' => 'Tempestad y/o lluvia con inundaciones de mediana intensidad.', 'f' => 1, 's' => 1, 'p' => 1, 'e' => 1, 'pb' => 1, 'if' => 1],
            ['no' => 'R08', 'riesgo' => 'Lesiones.', 'f' => 1, 's' => 1, 'p' => 1, 'e' => 1, 'pb' => 1, 'if' => 1],
            ['no' => 'R09', 'riesgo' => 'Amenazas a empleados.', 'f' => 1, 's' => 1, 'p' => 1, 'e' => 1, 'pb' => 1, 'if' => 1],
            ['no' => 'R10', 'riesgo' => 'Incendio.', 'f' => 1, 's' => 1, 'p' => 1, 'e' => 1, 'pb' => 1, 'if' => 1],
        ];

    }
    public $n;
    public function store($id_, $boton, $id)
    {
        $n = ReportTitleSubtitle::findOrFail($id_);
        $name = Subtitle::where('id',$n->subtitle_id)->value('nombre');

        $this->validate([
            'img1' => 'nullable|image|required_with:leyenda1',
            'img2' => 'nullable|image|required_with:leyenda2',
            'img3' => 'nullable|image|required_with:leyenda3',
            'leyenda1' => 'nullable|string|required_with:img1',
            'leyenda2' => 'nullable|string|required_with:img2',
            'leyenda3' => 'nullable|string|required_with:img3',
        ], [
            'img1.required_with'     => '⚠️ Si agregas una leyenda, también debes subir una imagen.',
            'leyenda1.required_with' => '⚠️ Si subes una imagen, también debes escribir una leyenda.',
            'img2.required_with'     => '⚠️ Si agregas una leyenda, también debes subir una imagen.',
            'leyenda2.required_with' => '⚠️ Si subes una imagen, también debes escribir una leyenda.',
            'img3.required_with'     => '⚠️ Si agregas una leyenda, también debes subir una imagen.',
            'leyenda3.required_with' => '⚠️ Si subes una imagen, también debes escribir una leyenda.',
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
            $content = Content::create([   
                'cont'     => $this->contenido,
                'r_t_s_id' => $id_,
                'img1'     => $path,
                'img2'     => $path2,
                'img3'     => $path3,
                'leyenda1' => $this->leyenda1,
                'leyenda2' => $this->leyenda2,
                'leyenda3' => $this->leyenda3,
            ]);
            if ($name == 'Mosler: Informe') {
                    // 👉 Crear los riesgos ligados al Content creado
            foreach ($this->riesgos as $riesgo) {
                AnalysisDiagrams::create([
                    'no'          => $riesgo['no'],
                    'riesgo'      => $riesgo['riesgo'],
                    'f'           => $riesgo['f'],
                    's'           => $riesgo['s'],
                    'p'           => $riesgo['p'],
                    'e'           => $riesgo['e'],
                    'pb'          => $riesgo['pb'],
                    'if'          => $riesgo['if'],
                    'f_ocurrencia'=> $this->calcularFOcurrencia($riesgo),
                    'contet_id'   => $content->id, // 👈 Ahora sí, se guarda con el id correcto
                ]);
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

    public function updatedValor($value)
    {
        // Validar al escribir manualmente
        if ($value < $this->min) {
            $this->valor = $this->min;
        } elseif ($value > $this->max) {
            $this->valor = $this->max;
        }
    }



    public function render()
    {
        return view('livewire.reports.botones-add.addc', [
            'RTitle' => $this->RTitle,
            'RSubtitle' => $this->RSubtitle,
            'RSection' => $this->RSection,
            'boton'  => $this->boton,
            'rp'  => $this->rp,
        ]);

    }


}
