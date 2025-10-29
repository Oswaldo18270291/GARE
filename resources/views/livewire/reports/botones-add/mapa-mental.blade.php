<div class="p-4" wire:ignore.self>
    <h2 class="text-xl font-bold text-center text-blue-800 mb-4">Mapa Mental - Interacción de Riesgos</h2>

    {{-- Agregar nodo --}}
    <div class="flex justify-center gap-3 mb-3">
        <input type="text" wire:model="nuevoNodo"
               placeholder="Nombre del nodo"
               class="border rounded px-2 py-1 text-sm focus:ring focus:ring-blue-300">
        <button type="button"
                wire:click="agregarNodoDesdeFront"
                class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-800">
            Agregar Nodo
        </button>
    </div>

    {{-- Conectar nodos --}}
    <div class="flex justify-center gap-3 mb-4">
        <select wire:model="nodoDesde" class="border px-2 py-1 rounded text-sm">
            <option value="">Desde...</option>
            @foreach ($nodos as $n)
                <option value="{{ $n['id'] }}">{{ $n['label'] }}</option>
            @endforeach
        </select>

        <select wire:model="nodoHasta" class="border px-2 py-1 rounded text-sm">
            <option value="">Hasta...</option>
            @foreach ($nodos as $n)
                <option value="{{ $n['id'] }}">{{ $n['label'] }}</option>
            @endforeach
        </select>

        <button type="button"
                wire:click="conectarNodos"
                class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-800">
            Conectar
        </button>
    </div>

    {{-- Contenedor del mapa --}}
    <div id="network"
         wire:ignore
         style="width:100%; height:600px; background:#f4f4f4; border-radius:10px; border:1px solid #ccc;">
    </div>
</div>

@once
    {{-- Cargar Vis.js UNA sola vez --}}
    <script src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>
@endonce

<script>
document.addEventListener('livewire:init', () => {
    const container = document.getElementById('network');
    if (!container) return;

    let nodes = new vis.DataSet(@json($nodos));
    let edges = new vis.DataSet(@json($relaciones));

    const data = { nodes, edges };
    const options = {
        nodes: {
            shape: 'ellipse',
            color: '#FFD700',
            font: { size: 14, color: '#222' },
            borderWidth: 2,
        },
        edges: {
            color: '#555',
            arrows: { to: { enabled: false } },
            smooth: true,
        },
        physics: {
            enabled: true,
            stabilization: { iterations: 200 }
        },
    };

    // 🔹 Inicializar red
    let network = new vis.Network(container, data, options);

    // 🔁 Escuchar actualizaciones desde Livewire
    window.Livewire.on('actualizarMapa', (data) => {
        console.log("Datos recibidos:", data);
        nodes.clear();
        edges.clear();
        nodes.add(data.nodos);
        edges.add(data.relaciones);
    });
});
</script>
