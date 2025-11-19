<?php

namespace App\Livewire\Reports;
use App\Models\ContentReference;
use App\Models\AnalysisDiagram;
use Livewire\Component;
use App\Models\Report;
use App\Models\ReportTitle;
use App\Models\ReportTitleSubtitle;
use App\Models\ReportTitleSubtitleSection;
use Illuminate\Support\Facades\Storage;
use App\Models\Content;
class Addcontenido extends Component
{
    public $report;
    public $content;
    public $diagram;


    public function mount($id)
    {
        $rep= Report::findOrFail($id);

        $this->authorize('update', $rep); // 👈 ahora sí se evalúa la policy
        // Cargar el reporte principal
        $this->report = Report::findOrFail($id);

        // Cargar títulos relacionados
    $this->report->titles = ReportTitle::with('title')
    ->join('titles', 'report_titles.title_id','=','titles.id')
    ->orderBy('titles.orden','asc')
    ->select('titles.*','report_titles.*')
    ->where('report_titles.report_id', $this->report->id)->where('report_titles.status',1)->get();

        foreach ($this->report->titles as $title) {
            // Cargar subtítulos de cada título
            $title->subtitles = ReportTitleSubtitle::with('subtitle')
            ->join('subtitles', 'report_title_subtitles.subtitle_id','=','subtitles.id')
            ->orderBy('subtitles.orden','asc')
            ->select('subtitles.*','report_title_subtitles.*')
            ->where('r_t_id', $title->id)->where('report_title_subtitles.status',1)->get();

            foreach ($title->subtitles as $subtitle) {
                // Cargar secciones de cada subtítulo
                $subtitle->sections = ReportTitleSubtitleSection::with('section')
                ->join('sections', 'report_title_subtitle_sections.section_id','=','sections.id')
                ->orderBy('sections.orden','asc')
                ->select('sections.*','report_title_subtitle_sections.*')
                ->where('r_t_s_id', $subtitle->id)->where('report_title_subtitle_sections.status',1)->get();
            }
        }
    }

    public function Addc_extend($id, $boton,$rp)
    {
        $this->redirectRoute('my_reports.addcontenido.Addc_extends', [
            'id' => $id, 
            'boton' => $boton,
            'rp' => $rp,
        ], navigate: true);
    }

    public function Editc_extend($id, $boton,$rp)
    {
        $this->redirectRoute('my_reports.addcontenido.Edit_extends', [
            'id' => $id, 
            'boton' => $boton,
            'rp' => $rp,
        ], navigate: true);
    }

    public function Addc($id, $boton,$rp)
    {
        $this->redirectRoute('my_reports.addcontenido.Addc', [
            'id' => $id, 
            'boton' => $boton,
            'rp' => $rp,
        ], navigate: true);
    }

    public function Editc($id, $boton,$rp)
    {
        $this->redirectRoute('my_reports.addcontenido.Editc', [
            'id' => $id, 
            'boton' => $boton,
            'rp' => $rp,
        ], navigate: true);
    }
    public $RTitle;
    public $contents;
    
public function deleteContent($id, $boton, $rp)
{
    // 🧮 Aquí iremos acumulando los números de referencia que se eliminan
    $numerosEliminados = [];

    if ($boton == 'tit') {

        $content = Content::where('r_t_id', $id)->first();
        $RTitle  = ReportTitle::findOrFail($id);

        if ($RTitle->title_id == 12 || $RTitle->title_id == 13) {

            $contents = Content::where('r_t_id', $id)->get();

            foreach ($contents as $content) {

                if (! $content) continue;

                // 1️⃣ Guardar qué números de referencia tenía este content
                $nums = ContentReference::where('content_id', $content->id)
                    ->pluck('numero')
                    ->toArray();
                $numerosEliminados = array_merge($numerosEliminados, $nums);

                // 2️⃣ Eliminar referencias de este contenido
                ContentReference::where('content_id', $content->id)->delete();

                // 3️⃣ Eliminar imágenes del JSON (img_block)
                $imagenes = $content->img_block;
                if (is_array($imagenes)) {
                    foreach ($imagenes as $img) {
                        if (!empty($img['src']) && Storage::disk('public')->exists($img['src'])) {
                            Storage::disk('public')->delete($img['src']);
                        }
                    }
                }

                // 4️⃣ Eliminar el registro completo
                $content->delete();
            }

        } else {

            if ($content) {
                // 1️⃣ Guardar qué números de referencia tenía este content
                $nums = ContentReference::where('content_id', $content->id)
                    ->pluck('numero')
                    ->toArray();
                $numerosEliminados = array_merge($numerosEliminados, $nums);

                // 2️⃣ Eliminar referencias de este contenido
                ContentReference::where('content_id', $content->id)->delete();

                // 3️⃣ Eliminar imágenes individuales
                foreach (['img1', 'img2', 'img3'] as $imgField) {
                    if ($content->$imgField && Storage::disk('public')->exists($content->$imgField)) {
                        Storage::disk('public')->delete($content->$imgField);
                    }
                }

                // 4️⃣ Si img_block es JSON con varias imágenes, también las borras
                if (is_array($content->img_block)) {
                    foreach ($content->img_block as $img) {
                        if (!empty($img['src']) && Storage::disk('public')->exists($img['src'])) {
                            Storage::disk('public')->delete($img['src']);
                        }
                    }
                }

                // 5️⃣ Eliminar el contenido
                $content->delete();
            }
        }

    } elseif ($boton == 'sub') {

        $content = Content::where('r_t_s_id', $id)->first();

        if ($content) {
            // 1️⃣ Guardar números de referencia de este content
            $nums = ContentReference::where('content_id', $content->id)
                ->pluck('numero')
                ->toArray();
            $numerosEliminados = array_merge($numerosEliminados, $nums);

            // 2️⃣ Eliminar referencias de este contenido
            ContentReference::where('content_id', $content->id)->delete();

            // 3️⃣ Eliminar registros asociados (ej. matriz de riesgos)
            AnalysisDiagram::where('content_id', $content->id)->delete();

            // 4️⃣ Eliminar imágenes
            foreach (['img1', 'img2', 'img3', 'img_grafica', 'img_map'] as $imgField) {
                if ($content->$imgField && Storage::disk('public')->exists($content->$imgField)) {
                    Storage::disk('public')->delete($content->$imgField);
                }
            }

            // 5️⃣ Eliminar el contenido
            $content->delete();
        }

    } elseif ($boton == 'sec') {

        $content = Content::where('r_t_s_s_id', $id)->first();

        if ($content) {
            // 1️⃣ Guardar números de referencia de este content
            $nums = ContentReference::where('content_id', $content->id)
                ->pluck('numero')
                ->toArray();
            $numerosEliminados = array_merge($numerosEliminados, $nums);

            // 2️⃣ Eliminar referencias de este contenido
            ContentReference::where('content_id', $content->id)->delete();

            // 3️⃣ Eliminar imágenes
            foreach (['img1', 'img2', 'img3'] as $imgField) {
                if ($content->$imgField && Storage::disk('public')->exists($content->$imgField)) {
                    Storage::disk('public')->delete($content->$imgField);
                }
            }

            // 4️⃣ Eliminar el contenido
            $content->delete();
        }
    }

    // 🔄 Si se eliminaron referencias, renumerar TODAS en el reporte y actualizar <sup>[n]</sup>
    if (!empty($numerosEliminados)) {
        $this->renumerarReferenciasDespuesDeEliminarContenido((int) $rp, $numerosEliminados);
    }

    session()->flash('eliminar', 'El contenido se eliminó correctamente.');
    $this->redirectRoute('my_reports.addcontenido', ['id' => $rp], navigate: true);
}

/**
 * Renumera todas las referencias del reporte después de eliminar
 * uno o varios contenidos.
 *
 * $reportId         -> id del reporte
 * $numerosEliminados -> array de números de referencia que desaparecieron (ej. [15,16])
 */
private function renumerarReferenciasDespuesDeEliminarContenido(int $reportId, array $numerosEliminados)
{
    if (empty($numerosEliminados)) {
        return;
    }

    sort($numerosEliminados);

    // 1️⃣ Renumerar en la tabla content_references
    $refs = ContentReference::query()
        ->whereHas('content', function ($q) use ($reportId) {
            $q->whereHas('reportTitle', fn($r) => $r->where('report_id', $reportId))
              ->orWhereHas('reportTitleSubtitle.reportTitle', fn($r) => $r->where('report_id', $reportId))
              ->orWhereHas('reportTitleSubtitleSection.reportTitleSubtitle.reportTitle', fn($r) => $r->where('report_id', $reportId));
        })
        ->orderBy('numero')
        ->get();

    $map = [];           // [numero_viejo => numero_nuevo]
    $contador = 1;

    foreach ($refs as $ref) {
        $old = (int) $ref->numero;
        $map[$old] = $contador;

        if ($old !== $contador) {
            $ref->numero = $contador;
            $ref->save();
        }

        $contador++;
    }

    // 2️⃣ Para los números que se ELIMINARON, marcarlos como null en el mapa
    foreach ($numerosEliminados as $n) {
        if (!array_key_exists($n, $map)) {
            $map[$n] = null;   // esto dirá: "esta referencia ya no existe, elimina su <sup>"
        }
    }

    // 3️⃣ Actualizar TODOS los contenidos del reporte (quitar/ajustar <sup>[n]</sup>)
    $contenidos = Content::query()
        ->whereHas('reportTitle', fn($r) => $r->where('report_id', $reportId))
        ->orWhereHas('reportTitleSubtitle.reportTitle', fn($r) => $r->where('report_id', $reportId))
        ->orWhereHas('reportTitleSubtitleSection.reportTitleSubtitle.reportTitle', fn($r) => $r->where('report_id', $reportId))
        ->get();

    foreach ($contenidos as $c) {
        $original = $c->cont ?? '';

        if (!is_string($original) || trim($original) === '') {
            continue;
        }

        $nuevo = $this->aplicarMapaASuperscripts($original, $map);

        if ($nuevo !== $original) {
            $c->cont = $nuevo;
            $c->save();
        }
    }
}

/**
 * Aplica un mapa [numViejo => numNuevo|null] a todos los <sup>[n]</sup> del HTML.
 * - Si numNuevo es null → elimina ese <sup>
 * - Si numNuevo es otro número → reemplaza [n] por [numNuevo]
 */
private function aplicarMapaASuperscripts(string $html, array $map): string
{
    if ($html === '' || empty($map)) {
        return $html;
    }

    $pat = '/<sup[^>]*>\s*\[(\d+)\]\s*<\/sup>/i';

    $nuevo = preg_replace_callback($pat, function ($m) use ($map) {
        $oldNum = (int) $m[1];

        if (!array_key_exists($oldNum, $map)) {
            // No hay cambio para este número
            return $m[0];
        }

        $newNum = $map[$oldNum];

        if ($newNum === null) {
            // Esta referencia fue eliminada → quitamos el <sup> completo
            return '';
        }

        // Cambiamos [oldNum] por [newNum] dentro del mismo <sup>
        return str_replace('['.$oldNum.']', '['.$newNum.']', $m[0]);
    }, $html);

    return $nuevo ?? $html;
}

    public function render()
    {
        return view('livewire.reports.addcontenido', [
            'report' => $this->report,
        ]);
    }
}
