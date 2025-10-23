<?php

namespace App\Livewire\History;

use Livewire\WithPagination;
use App\Models\Report;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public ?string $startDate = null;
    public ?string $endDate = null;

    public string $sortField = 'created_at'; // Campo inicial de ordenamiento
    public string $sortDirection = 'desc';   // Dirección inicial

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStartDate()
    {
        $this->resetPage();
    }

    public function updatingEndDate()
    {
        $this->resetPage();
    }

    public function sortBy(string $field)
    {
        if ($this->sortField === $field) {
            // Cambiar dirección si ya se está ordenando por este campo
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Cambiar de campo y poner orden ascendente por defecto
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function render()
    {
        if(Auth::user()->hasRole('admin'))
        {
            return view('livewire.history.index', [
                'reports' => Report::where('status', true)
                        ->when($this->search, function ($query) {
                            $query->where(function ($q) {
                                $q->where('nombre_empresa', 'ILIKE', "%{$this->search}%")
                                ->orWhere('representante', 'ILIKE', "%{$this->search}%")
                                ->orWhere('fecha_analisis', 'ILIKE', "%{$this->search}%");
                            });
                        })
                        ->when($this->startDate, fn($q) => $q->whereDate('created_at', '>=', $this->startDate))
                        ->when($this->endDate, fn($q) => $q->whereDate('created_at', '<=', $this->endDate))
                        ->orderBy($this->sortField, $this->sortDirection)
                        ->paginate(10),
                ]);
        }else
        {return view('livewire.history.index', [
                'reports' => Report::where('status', true)
                        ->where('user_id', Auth::id())
                        ->when($this->search, function ($query) {
                            $query->where(function ($q) {
                                $q->where('nombre_empresa', 'ILIKE', "%{$this->search}%")
                                ->orWhere('representante', 'ILIKE', "%{$this->search}%")
                                ->orWhere('fecha_analisis', 'ILIKE', "%{$this->search}%");
                            });
                        })
                        ->when($this->startDate, fn($q) => $q->whereDate('created_at', '>=', $this->startDate))
                        ->when($this->endDate, fn($q) => $q->whereDate('created_at', '<=', $this->endDate))
                        ->orderBy($this->sortField, $this->sortDirection)
                        ->paginate(10),
                ]);

        }
    }
}
