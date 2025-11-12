<div>
<div class="p-4">
    <table class="w-full border-collapse font-sans text-sm" style="border:1px solid #001a4d;">
        <thead>
            <tr class="text-center bg-[#002060] text-white">
                <th colspan="6" class="p-2 font-bold text-base">COTIZACIONES DE SISTEMAS TECNOLÓGICOS</th>
            </tr>
            <tr class="bg-[#002060] text-white">
                <th class="p-2 border">EMPRESA</th>
                <th class="p-2 border">CONCEPTO</th>
                <th class="p-2 border">CANT.</th>
                <th class="p-2 border">COSTO SIN IVA</th>
                <th class="p-2 border">COMENTARIOS</th>
                <th class="p-2 border"></th>
            </tr>
        </thead>

        <tbody>
            @forelse ($empresas as $eIndex => $empresa)
                @foreach ($empresa['items'] as $iIndex => $item)
                    <tr class="text-[12pt] border" style="background-color: {{ $empresa['color'] }}; border:1px solid #000000ff;">
                        {{-- 🔹 Columna de empresa solo en la primera fila --}}
                        @if ($iIndex == 0)
                            <td rowspan="{{ count($empresa['items']) }}" class="border font-semibold align-top p-2 text-left" style="border:1px solid #000000ff;">
                                <input type="text"
                                    wire:model="empresas.{{ $eIndex }}.nombre"
                                    placeholder="Nombre de la empresa"
                                    class="w-full text-xs border rounded p-1 mb-2"
                                    style="border-color:rgba(31, 89, 177, 1);">

                                <div class="flex items-center gap-2 mb-2">
                                    <label class="text-xs">Color:</label>
                                    <input type="color" wire:model="empresas.{{ $eIndex }}.color"
                                        class="w-6 h-6 cursor-pointer">
                                </div>

                                <div class="flex gap-1">
                                    {{-- ✅ botones tipo="button" para evitar submit --}}
                                    <button type="button" wire:click="agregarItem({{ $eIndex }})"
                                        class="text-xs bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded">
                                        <!--
                                            tags: [table, plus, add, insert, include, data-table, table-operation, table-add, add-to-table, include-in-table]
                                            category: Database
                                            version: "2.13"
                                            unicode: "fa1f"
                                            -->
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="40"
                                                height="40"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="#ffffffff"
                                                stroke-width="0.7"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            >
                                            <title>Agregar título</title>
                                            <path d="M12.5 21h-7.5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v7.5" />
                                            <path d="M3 10h18" />
                                            <path d="M10 3v18" />
                                            <path d="M16 19h6" />
                                            <path d="M19 16v6" />
                                            </svg>
                                    </button>

                                    <button type="button" wire:click="eliminarEmpresa({{ $eIndex }})"
                                        class="text-xs bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded">
                                        <!--
                                            tags: [table, column, data-table, spreadsheet, table-structure, columnar, information, grid, table-columnar, dataset]
                                            category: Database
                                            version: "2.24"
                                            unicode: "faff"
                                            -->
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="40"
                                                height="40"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="#ffffffff"
                                                stroke-width="0.7"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            >
                                            <title>Eliminar empresa</title>
                                            <path d="M3 5a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-14z" />
                                            <path d="M10 10h11" />
                                            <path d="M10 3v18" />
                                            <path d="M9 3l-6 6" />
                                            <path d="M10 7l-7 7" />
                                            <path d="M10 12l-7 7" />
                                            <path d="M10 17l-4 4" />
                                            </svg>
                                    </button>
                                </div>
                            </td>
                        @endif

                        {{-- Concepto --}}
                        <td class="border p-1" style="border:1px solid #000000ff;">
                            <input type="text"
                                wire:model="empresas.{{ $eIndex }}.items.{{ $iIndex }}.concepto"
                                placeholder="Concepto"
                                class="w-full text-xs border rounded p-1"
                                style="border-color:rgba(31, 89, 177, 1);">
                        </td>

                        {{-- Cantidad --}}
                        <td class="border p-1 text-center" style="border:1px solid #000000ff;">
                            <input type="text"
                                wire:model="empresas.{{ $eIndex }}.items.{{ $iIndex }}.cantidad"
                                placeholder="#"
                                class="w-16 text-xs border rounded p-1 text-center"
                                style="border-color:rgba(31, 89, 177, 1);">
                        </td>

                        {{-- Costo --}}
                        <td class="border p-1 text-center" style="border:1px solid #000000ff;">
                            <input type="text"
                                wire:model="empresas.{{ $eIndex }}.items.{{ $iIndex }}.costo"
                                placeholder="$"
                                class="w-32 text-xs border rounded p-1 text-center"
                                style="border-color:rgba(31, 89, 177, 1);">
                        </td>

                        {{-- Comentarios --}}
                        <td class="border p-1 text-center" style="border:1px solid #000000ff;">
                            <input type="text"
                                wire:model="empresas.{{ $eIndex }}.items.{{ $iIndex }}.comentarios"
                                placeholder="Comentarios"
                                class="w-full text-xs border rounded p-1 text-center mb-1"
                                style="border-color:rgba(31, 89, 177, 1);">
                        </td>
                        {{-- Eliminar fila --}}
                        <td style="padding: 4px;">
                            {{-- ❌ eliminar fila --}}
                            <button type="button" wire:click="eliminarItem({{ $eIndex }}, {{ $iIndex }})"
                                class="text-xs bg-red-600 hover:bg-red-700 font-bold hover:underline rounded">
                                <!--
                                    tags: [table, row, data-table, spreadsheet, table-structure, row-wise, information, grid, table-row-wise, dataset]
                                    category: Database
                                    version: "2.24"
                                    unicode: "fb00"
                                    -->
                                    <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="40"
                                    height="40"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="#ffffffff"
                                    stroke-width="0.7"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    >
                                    <title>Eliminar fila</title>
                                    <path d="M3 5a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-14z" />
                                    <path d="M9 3l-6 6" />
                                    <path d="M14 3l-7 7" />
                                    <path d="M19 3l-7 7" />
                                    <path d="M21 6l-4 4" />
                                    <path d="M3 10h18" />
                                    <path d="M10 10v11" />
                                    </svg>
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
    <br>
    <div class="flex justify-end mb-4">
        {{-- 🔹 Botón principal para agregar empresa --}}
        <button type="button" wire:click="agregarEmpresa"
            class="bg-teal-600 hover:bg-teal-800 text-white font-semibold px-4 py-2 rounded">
            + Agregar Empresa
        </button>
    </div>
</div>

    @foreach ($bloques as $i => $bloque)
        <div class="border rounded-lg p-4 bg-white shadow space-y-4 mb-4" style="border-color: #001a4d;">
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
                    if (typeof Quill === 'undefined') { 
                        return setTimeout(init, 150); 
                    }

                    const quill = new Quill($refs.editorTit, {
                        theme: 'snow',
                        modules: {
                            toolbar: {
                                container: [
                                    [{ header: [1, 2, false] }],
                                    ['bold', 'italic', 'underline'],
                                    [{ 'align': [] }],
                                    [{ list: 'ordered' }, { list: 'bullet' }],
                                    [{ script: 'sub' }, { script: 'super' }],
                                    ['clean']
                                ]
                            }
                        }
                    });

                    quill.root.innerHTML = @js($contenido ?? '');

                    quill.on('text-change', () => {
                        const html = quill.root.innerHTML;
                        $refs.textareaTit.value = html;
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
                    <div class="border p-3 rounded-lg bg-gray-50" style="border-color: #0f4569ff;">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-medium text-gray-700">Imagen {{ $j + 1 }}</h3>
                            <button type="button" wire:click="eliminarImagen({{ $i }}, {{ $j }})"
                                class="text-sm text-red-600 hover:underline">🗑 Quitar</button>
                        </div>

                        <label class="block text-sm font-medium text-gray-700">Título</label>
                        <input type="text" wire:model="bloques.{{ $i }}.imagenes.{{ $j }}.leyenda"
                            class="mt-1 w-full border rounded px-2 py-1 text-sm"
                            placeholder="Ingrese título de la imagen" style="border-color:rgba(31, 89, 177, 1);">
                        
                        <label class="block text-sm font-medium text-gray-700 mt-3">Archivo</label>
                        <input type="file" wire:model="bloques.{{ $i }}.imagenes.{{ $j }}.img" accept="image/*"
                            class="text-gray-500 border rounded p-1 cursor-pointer w-full text-sm"
                            style="border-color:rgba(31, 89, 177, 1);">

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

    {{-- Botón agregar bloque --}}
    <button type="button" wire:click="agregarBloque"
        class="px-4 py-2 text-white rounded bg-teal-600 hover:bg-teal-800 mb-4 ml-auto block">
        + Agregar bloque
    </button>

    {{-- Botón guardar --}}
    <div class="text-left pt-4">
        <button type="button" wire:click="store('{{ $RTitle->id ?? $RSubtitle->id ?? $RSection->id }}', '{{ $boton }}', '{{ $rp }}')"
            class="px-6 py-2 bg-success border border-success text-white rounded hover:opacity-75">
                Guardar
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
