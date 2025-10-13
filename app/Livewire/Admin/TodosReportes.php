<?php

namespace App\Livewire\Admin;

use Livewire\WithPagination;
use App\Models\Report;
use App\Models\ReportTitle;
use App\Models\ReportTitleSubtitle;
use App\Models\ReportTitleSubtitleSection;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class TodosReportes extends Component
{
    use WithPagination;

    public string $search = '';

    // Filtros de fecha
    public ?string $startCreated = null;
    public ?string $endCreated   = null;
    public ?string $startAnalysis = null;
    public ?string $endAnalysis   = null;

    // Ordenamiento
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStartCreated() { $this->resetPage(); }
    public function updatingEndCreated() { $this->resetPage(); }
    public function updatingStartAnalysis() { $this->resetPage(); }
    public function updatingEndAnalysis() { $this->resetPage(); }

    public function sortBy(string $field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function status($id)
    {
        $report = Report::findOrFail($id);
        $report->status = true;
        $report->save();
        session()->flash('success', 'El informe se ha finalizado');
        $this->redirectRoute('history.index', navigate: true);
    }

    public function delete($id)
    {
        $report = Report::findOrFail($id);

        if ($report->logo && Storage::disk('public')->exists($report->logo)) {
            Storage::disk('public')->delete($report->logo);
        }
        if ($report->img && Storage::disk('public')->exists($report->img)) {
            Storage::disk('public')->delete($report->img);
        }

        $reportTitles = ReportTitle::where('report_id', $report->id)->get();

        foreach ($reportTitles as $title) {
            foreach ($title->contents as $content) {
                foreach (['img1', 'img2', 'img3'] as $imgField) {
                    if ($content->$imgField && Storage::disk('public')->exists($content->$imgField)) {
                        Storage::disk('public')->delete($content->$imgField);
                    }
                }
                $content->delete();
            }

            $subtitles = ReportTitleSubtitle::where('r_t_id', $title->id)->get();

            foreach ($subtitles as $subtitle) {
                foreach ($subtitle->contents as $content) {
                    foreach (['img1', 'img2', 'img3'] as $imgField) {
                        if ($content->$imgField && Storage::disk('public')->exists($content->$imgField)) {
                            Storage::disk('public')->delete($content->$imgField);
                        }
                    }
                    $content->delete();
                }

                $sections = ReportTitleSubtitleSection::where('r_t_s_id', $subtitle->id)->get();

                foreach ($sections as $section) {
                    foreach ($section->contents as $content) {
                        foreach (['img1', 'img2', 'img3'] as $imgField) {
                            if ($content->$imgField && Storage::disk('public')->exists($content->$imgField)) {
                                Storage::disk('public')->delete($content->$imgField);
                            }
                        }
                        $content->delete();
                    }
                    $section->delete();
                }

                $subtitle->delete();
            }
        }

        ReportTitle::where('report_id', $report->id)->delete();
        $report->delete();

        session()->flash('eliminar', 'El informe y sus relaciones se eliminaron correctamente.');
    }

    public function render()
    {
        return view('livewire.admin.todos-reportes', [
            'reports' => Report::where('status', false)
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('nombre_empresa', 'ILIKE', "%{$this->search}%")
                          ->orWhere('representante', 'ILIKE', "%{$this->search}%")
                          ->orWhereHas('user', function ($q2) {
                              $q2->where('name', 'ILIKE', "%{$this->search}%");
                          });
                    });
                })
                // Filtro por fecha de creación
                ->when($this->startCreated, fn($q) => $q->whereDate('created_at', '>=', $this->startCreated))
                ->when($this->endCreated, fn($q) => $q->whereDate('created_at', '<=', $this->endCreated))
                // Filtro por fecha de análisis
                ->when($this->startAnalysis, fn($q) => $q->whereDate('fecha_analisis', '>=', $this->startAnalysis))
                ->when($this->endAnalysis, fn($q) => $q->whereDate('fecha_analisis', '<=', $this->endAnalysis))
                ->with('user')
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(10),
        ]);
    }

    public function addcontent($id)
    {
        $this->redirectRoute('my_reports.addcontenido', ['id' => $id], navigate: true);
    }

    public function editeditstructure($id)
    {
        $this->redirectRoute('my_reports.editestructura', ['id' => $id], navigate: true);
    }
}
