<div>
    {{-- Botón agregar bloque --}}
    <button type="button" wire:click="agregarBloque"
        class="px-4 py-2 bg-blue-700 text-white rounded hover:bg-blue-800 mb-4">
        + Agregar bloque
    </button>
<div class="p-4">
    <div class="flex justify-end mb-4">
        {{-- 🔹 Botón principal para agregar empresa --}}
        <button type="button" wire:click="agregarEmpresa"
            class="bg-blue-700 hover:bg-blue-800 text-white font-semibold px-4 py-2 rounded">
            + Agregar Empresa
        </button>
    </div>

    <table class="w-full border-collapse font-sans text-sm" style="border:1px solid #001a4d;">
        <thead>
            <tr class="text-center bg-[#002060] text-white">
                <th colspan="5" class="p-2 font-bold text-base">COTIZACIONES DE SISTEMAS TECNOLÓGICOS</th>
            </tr>
            <tr class="bg-[#002060] text-white">
                <th class="p-2 border">EMPRESA</th>
                <th class="p-2 border">CONCEPTO</th>
                <th class="p-2 border">CANT.</th>
                <th class="p-2 border">COSTO SIN IVA</th>
                <th class="p-2 border">COMENTARIOS</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($empresas as $eIndex => $empresa)
                @foreach ($empresa['items'] as $iIndex => $item)
                    <tr style="background-color: {{ $empresa['color'] }};">
                        {{-- 🔹 Columna de empresa solo en la primera fila --}}
                        @if ($iIndex == 0)
                            <td rowspan="{{ count($empresa['items']) }}" class="border font-semibold align-top p-2 text-left">
                                <input type="text"
                                    wire:model="empresas.{{ $eIndex }}.nombre"
                                    placeholder="Nombre de la empresa"
                                    class="w-full text-xs border rounded p-1 mb-2">

                                <div class="flex items-center gap-2 mb-2">
                                    <label class="text-xs">Color:</label>
                                    <input type="color" wire:model="empresas.{{ $eIndex }}.color"
                                        class="w-6 h-6 cursor-pointer">
                                </div>

                                <div class="flex flex-col gap-1">
                                    {{-- ✅ botones tipo="button" para evitar submit --}}
                                    <button type="button" wire:click="agregarItem({{ $eIndex }})"
                                        class="text-xs bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded">
                                        + Agregar Fila
                                    </button>

                                    <button type="button" wire:click="eliminarEmpresa({{ $eIndex }})"
                                        class="text-xs bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded">
                                        Eliminar Empresa
                                    </button>
                                </div>
                            </td>
                        @endif

                        {{-- Concepto --}}
                        <td class="border p-1">
                            <input type="text"
                                wire:model="empresas.{{ $eIndex }}.items.{{ $iIndex }}.concepto"
                                placeholder="Concepto"
                                class="w-full text-xs border rounded p-1">
                        </td>

                        {{-- Cantidad --}}
                        <td class="border p-1 text-center">
                            <input type="text"
                                wire:model="empresas.{{ $eIndex }}.items.{{ $iIndex }}.cantidad"
                                placeholder="#"
                                class="w-16 text-xs border rounded p-1 text-center">
                        </td>

                        {{-- Costo --}}
                        <td class="border p-1 text-center">
                            <input type="text"
                                wire:model="empresas.{{ $eIndex }}.items.{{ $iIndex }}.costo"
                                placeholder="$"
                                class="w-32 text-xs border rounded p-1 text-center">
                        </td>

                        {{-- Comentarios --}}
                        <td class="border p-1 text-center">
                            <input type="text"
                                wire:model="empresas.{{ $eIndex }}.items.{{ $iIndex }}.comentarios"
                                placeholder="Comentarios"
                                class="w-full text-xs border rounded p-1 text-center mb-1">

                            {{-- ❌ eliminar fila --}}
                            <button type="button" wire:click="eliminarItem({{ $eIndex }}, {{ $iIndex }})"
                                class="text-xs text-red-700 font-bold hover:underline">
                                ✕
                            </button>
                        </td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="5" class="text-center text-gray-500 italic p-4">
                        No hay empresas registradas. Usa el botón “+ Agregar Empresa”.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

    @foreach ($bloques as $i => $bloque)
        <div class="border rounded-lg p-4 bg-white shadow space-y-4 mb-4">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-blue-800">Bloque {{ $i + 1 }}</h2>
                <button type="button" wire:click="eliminarBloque({{ $i }})"
                    class="text-red-600 hover:underline">🗑 Eliminar</button>
            </div>

   {{-- Editor Quill adaptado para múltiples bloques --}}
<div 
    x-data 
    wire:ignore
    x-init="
        const init = () => {
            if (typeof Quill === 'undefined') { return setTimeout(init, 150); }

            const quill = new Quill($refs.editorTit, {
                theme: 'snow',
                modules: {
                    toolbar: {
                        container: [
                            [{ header: [1, 2, false] }],
                            ['bold', 'italic', 'underline'],
                            [{ 'align': [] }],
                            [{ list: 'ordered' }, { list: 'bullet' }],
                            ['clean']
                        ]
                    }
                }
            });

            const toolbar = quill.getModule('toolbar').container;
            const editor  = quill.root;

            // --- Botón 'a.' (lista alfabética)
            const alphaButton = document.createElement('button');
            alphaButton.innerHTML = `
                <svg viewBox='0 0 18 18'>
                  <text x='4' y='14' font-size='13' font-family='Arial'>a.</text>
                </svg>`;
            alphaButton.type = 'button';
            alphaButton.classList.add('ql-alpha');
            alphaButton.title = 'Lista alfabética';

            const orderedBtn = toolbar.querySelector('.ql-list[value=ordered]');
            orderedBtn?.insertAdjacentElement('afterend', alphaButton);

            // Al hacer clic en 'a.' -> forzar alfabética
            alphaButton.addEventListener('click', () => {
                const range = quill.getSelection();
                if (!range) return;

                const fmt = quill.getFormat(range);
                if (!fmt.list || editor.classList.contains('alpha-list') === false) {
                    quill.format('list', 'ordered');
                    editor.classList.add('alpha-list');
                    alphaButton.classList.add('ql-active');
                } else {
                    quill.format('list', false);
                    editor.classList.remove('alpha-list');
                    alphaButton.classList.remove('ql-active');
                }
            });

            // Al hacer clic en el botón numerado -> quitar alpha-list
            orderedBtn?.addEventListener('click', () => {
                editor.classList.remove('alpha-list');
                alphaButton.classList.remove('ql-active');
            });

            // Detectar cambios de formato
            quill.on('editor-change', () => {
                const range = quill.getSelection();
                if (!range) return;
                const fmt = quill.getFormat(range);
                if (fmt.list === 'ordered') {
                    editor.classList.remove('alpha-list');
                    alphaButton.classList.remove('ql-active');
                }
            });

            // --- CSS personalizado para listas alfabéticas
            const style = document.createElement('style');
            style.innerHTML = `
              .ql-toolbar button.ql-alpha svg { width: 18px; height: 18px; }
              .ql-editor ol > li::before { content: counter(list-0, decimal) '. '; }
              .ql-editor.alpha-list ol > li::before { content: counter(list-0, lower-alpha) '. '; }
              .ql-editor.alpha-list ol ol > li::before { content: counter(list-1, lower-alpha) '. '; }
              .ql-editor.alpha-list ol ol ol > li::before { content: counter(list-2, lower-alpha) '. '; }
              .ql-editor.alpha-list ol ol ol ol > li::before { content: counter(list-3, lower-alpha) '. '; }
              .ql-editor.alpha-list ol ol ol ol ol > li::before { content: counter(list-4, lower-alpha) '. '; }
            `;
            document.head.appendChild(style);

            // --- Cargar contenido existente del bloque
            quill.root.innerHTML = @js($bloque['contenido'] ?? '');

            // --- Sincroniza con Livewire
            quill.on('text-change', () => {
                $refs.textareaTit.value = quill.root.innerHTML;
                $refs.textareaTit.dispatchEvent(new Event('input'));
            });
        };
        init();
    "
>
    <div x-ref="editorTit" style="height:200px; background:white;"></div>
    <textarea x-ref="textareaTit" wire:model="bloques.{{ $i }}.contenido" class="hidden"></textarea>
</div>




            {{-- Imágenes --}}
            <div class="space-y-4">
                @foreach ($bloque['imagenes'] as $j => $img)
                    <div class="border p-3 rounded-lg bg-gray-50">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-medium text-gray-700">Imagen {{ $j + 1 }}</h3>
                            <button type="button" wire:click="eliminarImagen({{ $i }}, {{ $j }})"
                                class="text-sm text-red-600 hover:underline">🗑 Quitar</button>
                        </div>

                        <label class="block text-sm font-medium text-gray-700">Título</label>
                        <input type="text" wire:model="bloques.{{ $i }}.imagenes.{{ $j }}.leyenda"
                            class="mt-1 w-full border rounded px-2 py-1 text-sm"
                            placeholder="Ingrese título de la imagen">

                        <label class="block text-sm font-medium text-gray-700 mt-3">Archivo</label>
                        <input type="file" wire:model="bloques.{{ $i }}.imagenes.{{ $j }}.img" accept="image/*">

                        @if ($img['img'])
                            <img src="{{ $img['img']->temporaryUrl() }}" class="w-32 h-32 object-cover rounded mt-2 border">
                        @endif
                    </div>
                @endforeach

                <button type="button" wire:click="agregarImagen({{ $i }})"
                    class="text-blue-700 hover:underline text-sm">
                    + Agregar imagen
                </button>
            </div>
        </div>
    @endforeach

    {{-- Botón guardar --}}
    <div class="text-center pt-4">
        <button type="button" wire:click="store('{{ $RTitle->id ?? $RSubtitle->id ?? $RSection->id }}', '{{ $boton }}', '{{ $rp }}')"
            class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">
            💾 Guardar todo
        </button>
    </div>

    {{-- Mensaje de éxito --}}
    @if (session('mensaje'))
        <div class="text-center text-green-600 font-semibold mt-3">
            {{ session('mensaje') }}
        </div>
    @endif

    {{-- Cargar Quill --}}
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
</div>
