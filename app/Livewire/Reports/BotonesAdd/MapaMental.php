<?php

namespace App\Livewire\Reports\BotonesAdd;

use Livewire\Component;

class MapaMental extends Component
{
    public $nodos = [];
    public $relaciones = [];
    public $nuevoNodo = '';
    public $colorNodo = '#FFD700';
    public $colorLetra = '#111111';
    public $tamanoLetra = 14;
    public $nodoDesde = '';
    public $nodoHasta = '';

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



    public function render()
    {
        return view('livewire.reports.botones-add.mapa-mental');
    }
}
