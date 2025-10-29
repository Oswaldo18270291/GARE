<div class="p-4">
    <h2 class="text-xl font-bold text-center text-blue-800 mb-4">
        Mapa Mental - Interacción de Riesgos
    </h2>

    {{-- Agregar nodo --}}
    <div class="flex flex-wrap justify-center gap-3 mb-3">
        <input type="text" wire:model.defer="nuevoNodo"
               placeholder="Nombre del nodo"
               class="border rounded px-2 py-1 text-sm focus:ring focus:ring-blue-300 w-40">

        <label class="flex items-center gap-1 text-sm">
            🎨 Fondo:
            <input type="color" wire:model="colorNodo" class="w-8 h-8 border rounded">
        </label>

        <label class="flex items-center gap-1 text-sm">
            🖋 Letra:
            <input type="color" wire:model="colorLetra" class="w-8 h-8 border rounded">
        </label>

        <label class="flex items-center gap-1 text-sm">
            🔠 Tamaño:
            <input type="number" wire:model="tamanoLetra" min="8" max="30"
                   class="w-16 border rounded px-1 py-0.5 text-center text-sm">
        </label>

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
        {{-- Botones de eliminación --}}
        <div class="flex justify-center gap-3 mb-4">
            <button id="btnEliminarNodo"
                    class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-800 disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                🗑️ Eliminar Nodo Seleccionado
            </button>

            <button id="btnEliminarConexion"
                    class="bg-orange-600 text-white px-3 py-1 rounded hover:bg-orange-800 disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                🔗 Eliminar Conexión Seleccionada
            </button>
        </div>
        <div class="flex justify-center items-center gap-3 mb-4">
            <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                🖼️ Fondo del mapa:
                <input type="file" id="inputFondo" accept="image/*"
                    class="text-sm border rounded p-1 cursor-pointer">
            </label>

            <button id="btnQuitarFondo"
                    type="button"
                    class="bg-gray-600 text-white px-3 py-1 rounded hover:bg-gray-800 disabled:opacity-50">
                Quitar Fondo
            </button>
        </div>
    </div>
<label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
    🌫️ Opacidad:
    <input type="range" id="rangoOpacidad" min="0" max="100" value="40"
           class="cursor-pointer w-32 accent-blue-700">
</label>
    {{-- Contenedor del mapa --}}
   <div class="relative w-full h-[600px] rounded-lg border border-gray-300 overflow-hidden">
    {{-- Fondo con imagen y opacidad --}}
    <div id="network-bg"
         class="absolute inset-0 bg-gray-100 bg-center bg-cover bg-no-repeat transition-all duration-500"
         style="opacity: 0.4;"> {{-- 🔹 Ajusta opacidad aquí --}}
    </div>

    {{-- Canvas del mapa --}}
    <div id="network"
         wire:ignore
         class="absolute inset-0 w-full h-full">
    </div>
</div>

@once
<script src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>
@endonce

<script>
document.addEventListener('livewire:init', () => {
    const container = document.getElementById('network');
    if (!container || typeof vis === 'undefined') return;

    let nodes = new vis.DataSet(@json($nodos));
    let edges = new vis.DataSet(@json($relaciones));

    const data = { nodes, edges };
    const options = {
        nodes: {
            shape: 'circle',
            size: 40,
            borderWidth: 2,
            shadow: true,
            color: {
                background: '#FFD700',
                border: '#b8860b',
                highlight: { background: '#FFED4A', border: '#D97706' },
            },
            font: {
                color: '#111',
                face: 'Arial',
                size: 14,
                vadjust: 0,
                align: 'center',
            },
        },
        edges: {
            color: { color: '#888' },
            smooth: { enabled: true, type: 'dynamic' },
            width: 2,
            selectionWidth: 4,
        },
        physics: { enabled: true, stabilization: { iterations: 150 } },
        interaction: { hover: true, multiselect: true, dragView: true, zoomView: true },
    };

    let network = new vis.Network(container, data, options);

    // Botones de eliminar
    const btnEliminarNodo = document.getElementById('btnEliminarNodo');
    const btnEliminarConexion = document.getElementById('btnEliminarConexion');

    // Actualizar desde Livewire
    window.Livewire.on('actualizarMapa', (payload) => {
        nodes.clear();
        edges.clear();
        if (payload?.nodos?.length) nodes.add(payload.nodos);
        if (payload?.relaciones?.length) edges.add(payload.relaciones);
        network.fit({ animation: { duration: 300 } });
    });

    // Mantener selección actual
    let seleccionActual = { nodo: null, conexion: null };

    network.on('selectNode', function (params) {
        seleccionActual.nodo = params.nodes[0];
        seleccionActual.conexion = null;
        btnEliminarNodo.disabled = false;
        btnEliminarConexion.disabled = true;
    });

    network.on('selectEdge', function (params) {
        if (params.edges.length > 0) {
            const edgeId = params.edges[0];
            const edgeData = edges.get(edgeId);
            seleccionActual.nodo = null;
            seleccionActual.conexion = edgeData;
            btnEliminarNodo.disabled = true;
            btnEliminarConexion.disabled = false;
        }
    });

    network.on('deselectNode', () => {
        seleccionActual.nodo = null;
        btnEliminarNodo.disabled = true;
    });

    network.on('deselectEdge', () => {
        seleccionActual.conexion = null;
        btnEliminarConexion.disabled = true;
    });

    // Doble clic para renombrar
    network.on('doubleClick', function (params) {
        if (params.nodes.length === 1) {
            const nodeId = params.nodes[0];
            const nuevoNombre = prompt("Nuevo nombre para el nodo:");
            if (nuevoNombre && nuevoNombre.trim() !== "") {
                @this.call('actualizarNodo', nodeId, nuevoNombre.trim());
            }
        }
    });

    // Clic derecho (acciones rápidas)
    network.on('oncontext', function (params) {
        params.event.preventDefault();
        const nodeId = network.getNodeAt(params.pointer.DOM);
        if (!nodeId) return;

        const opcion = prompt(
            "Acción para el nodo:\n1. Cambiar color fondo\n2. Cambiar color de letra\n3. Eliminar nodo"
        );

        if (opcion === "1") {
            const nuevoColor = prompt("Nuevo color de fondo (#RRGGBB):", "#FF0000");
            if (nuevoColor && /^#([0-9A-F]{3}){1,2}$/i.test(nuevoColor.trim())) {
                @this.call('actualizarColorNodo', nodeId, nuevoColor.trim());
            }
        } else if (opcion === "2") {
            const nuevoColor = prompt("Nuevo color de letra (#RRGGBB):", "#000000");
            if (nuevoColor && /^#([0-9A-F]{3}){1,2}$/i.test(nuevoColor.trim())) {
                const nodo = nodes.get(nodeId);
                if (nodo) {
                    nodo.font.color = nuevoColor.trim();
                    @this.call('actualizarNodo', nodeId, nodo.label);
                }
            }
        } else if (opcion === "3") {
            if (confirm("¿Seguro que deseas eliminar solo este nodo y sus conexiones?")) {
                @this.call('eliminarNodoSeleccionado', nodeId);
            }
        }
    });

    // Eliminar nodo con botón
    btnEliminarNodo.addEventListener('click', () => {
        if (seleccionActual.nodo) {
            if (confirm("¿Eliminar nodo seleccionado y sus conexiones?")) {
                @this.call('eliminarNodoSeleccionado', seleccionActual.nodo);
                seleccionActual.nodo = null;
                btnEliminarNodo.disabled = true;
            }
        } else {
            alert("Selecciona un nodo primero.");
        }
    });


    // Eliminar conexión con botón  
    btnEliminarConexion.addEventListener('click', () => {
        if (seleccionActual.conexion) {
            if (confirm("¿Eliminar SOLO la conexión seleccionada?")) {
                @this.call(
                    'eliminarRelacionSeleccionada',
                    seleccionActual.conexion.from,
                    seleccionActual.conexion.to
                );
                seleccionActual.conexion = null;
                btnEliminarConexion.disabled = true;
                network.unselectAll(); // 🔹 Limpia la selección visual
            }
        } else {
            alert("Selecciona una conexión primero.");
        }
    });

});
// Fondo personalizado del mapa con opacidad
const inputFondo = document.getElementById('inputFondo');
const btnQuitarFondo = document.getElementById('btnQuitarFondo');
const networkBackground = document.getElementById('network-bg');

inputFondo.addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (e) {
        const imageUrl = e.target.result;
        networkBackground.style.backgroundImage = `url('${imageUrl}')`;
        networkBackground.style.backgroundSize = 'cover';
        networkBackground.style.backgroundPosition = 'center';
        networkBackground.style.backgroundRepeat = 'no-repeat';
        networkBackground.style.opacity = '0.4'; // 🔹 Nivel de opacidad
    };
    reader.readAsDataURL(file);
});

btnQuitarFondo.addEventListener('click', () => {
    networkBackground.style.backgroundImage = 'none';
});
const rangoOpacidad = document.getElementById('rangoOpacidad');
rangoOpacidad.addEventListener('input', () => {
    const valor = rangoOpacidad.value / 100;
    networkBackground.style.opacity = valor.toString();
});

</script>
