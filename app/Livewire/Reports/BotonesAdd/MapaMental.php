<?php

namespace App\Livewire\Reports\BotonesAdd;

use Livewire\Component;

class MapaMental extends Component
{
    public $nodos = [];
    public $relaciones = [];
    public $nuevoNodo = '';
    public $nodoDesde = '';
    public $nodoHasta = '';

    public function agregarNodoDesdeFront()
    {
        if (trim($this->nuevoNodo) === '') return;

        $nuevoId = count($this->nodos) + 1;
        $this->nodos[] = [
            'id' => $nuevoId,
            'label' => $this->nuevoNodo,
            'color' => '#FFD700',
        ];
        $this->nuevoNodo = '';

        $this->dispatch('actualizarMapa', nodos: $this->nodos, relaciones: $this->relaciones);
    }

    public function conectarNodos()
    {
        if (!$this->nodoDesde || !$this->nodoHasta) return;

        $this->relaciones[] = [
            'from' => (int)$this->nodoDesde,
            'to'   => (int)$this->nodoHasta,
        ];

        $this->dispatch('actualizarMapa', nodos: $this->nodos, relaciones: $this->relaciones);
    }

    public function render()
    {
        return view('livewire.reports.botones-add.mapa-mental');
    }
}
