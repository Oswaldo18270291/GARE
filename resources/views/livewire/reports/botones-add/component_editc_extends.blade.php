
<div>
    @if($titulo===12)
<div class="mt-6">
    <table class="w-full border-collapse text-sm" style="border:1px solid #001a4d;">
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
                    <tr style="background-color: {{ $empresa['color'] }};" style="border:1px solid #000000ff;">
                        @if ($iIndex == 0)
                            <td rowspan="{{ count($empresa['items']) }}" class="border p-2 align-top font-semibold" style="border:1px solid #000000ff;">
                                <input type="text" wire:model="empresas.{{ $eIndex }}.nombre"
                                    placeholder="Nombre de empresa" class="w-full border rounded text-xs p-1 mb-2"style="border-color:rgba(31, 89, 177, 1);">

                                <input type="color" wire:model="empresas.{{ $eIndex }}.color"
                                    class="w-6 h-6 cursor-pointer mb-2">

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

                        <td class="border p-1" style="border:1px solid #000000ff;">
                            <input type="text" wire:model="empresas.{{ $eIndex }}.items.{{ $iIndex }}.concepto"
                                placeholder="Concepto" class="w-full text-xs border rounded p-1" style="border-color:rgba(31, 89, 177, 1);">
                        </td>
                        <td class="border p-1 text-center" style="border:1px solid #000000ff;">
                            <input type="text" wire:model="empresas.{{ $eIndex }}.items.{{ $iIndex }}.cantidad"
                                class="w-16 text-xs border rounded p-1 text-center" style="border-color:rgba(31, 89, 177, 1);">
                        </td>
                        <td class="border p-1 text-center" style="border:1px solid #000000ff;">
                            <input type="text" wire:model="empresas.{{ $eIndex }}.items.{{ $iIndex }}.costo"
                                class="w-32 text-xs border rounded p-1 text-center" style="border-color:rgba(31, 89, 177, 1);">
                        </td>
                        <td class="border p-1 text-center" style="border:1px solid #000000ff;">
                            <input type="text" wire:model="empresas.{{ $eIndex }}.items.{{ $iIndex }}.comentarios"
                                class="w-full text-xs border rounded p-1 text-center mb-1" style="border-color:rgba(31, 89, 177, 1);">
                        </td>
                        <td style="padding: 4px; border:1px solid #000000ff;"">
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
                        No hay empresas registradas.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <br>
    <div class="flex justify-end mb-4">
        <button type="button" wire:click="agregarEmpresa"
            class="bg-teal-600 hover:bg-teal-800 text-white font-semibold px-4 py-2 rounded">
            + Agregar Empresa
        </button>
    </div>
</div>
@endif
    <form wire:submit.prevent="update" class="space-y-6">

        @foreach ($bloques as $i => $bloque)
            <div class="border rounded-lg p-4 bg-white shadow space-y-4" style="border-color: #001a4d;">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-blue-800">Bloque {{ $i + 1 }}</h2>
                <button type="button" wire:click="eliminarBloque({{ $i }})"
                    class="text-red-600 hover:underline">🗑 Eliminar</button>
            </div>

                {{-- Editor Quill --}}
                <div wire:ignore x-data x-init="
                    const init = () => {
                        if (typeof Quill === 'undefined') return setTimeout(init, 150);
                        const quill = new Quill($refs.editor{{ $i }}, {
                            theme: 'snow',
                            modules: {
                                toolbar: [
                                    [{ header: [1, 2, false] }],
                                    ['bold', 'italic', 'underline'],
                                    [{ 'align': [] }],
                                    [{ list: 'ordered' }, { list: 'bullet' }],
                                    [{ script: 'sub' }, { script: 'super' }],
                                    ['clean']
                                ]
                            }
                        });
                        // 🚀 Detectar Shift + Enter manualmente (como tu versión original)
quill.root.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && e.shiftKey) {
        e.preventDefault(); // Evita que Quill cree otra viñeta

        const range = quill.getSelection(true);
        if (!range) return;

        const [block] = quill.scroll.descendant(Quill.import('blots/block'), range.index);
        const formats = quill.getFormat(range);

        // 🔹 Insertar salto de línea dentro del mismo <li>
        quill.insertEmbed(range.index, 'text', '\n', Quill.sources.USER);

        // 🔹 Mantener indentación y formato del punto actual
        quill.formatLine(range.index + 1, formats);

        // 🔹 Reubicar cursor
        quill.setSelection(range.index + 1, Quill.sources.SILENT);
    }
});

function getHtmlWithBreaks() {
    let html = quill.root.innerHTML;

    // 🔹 Reemplaza saltos de línea (\n) por <br>
    html = html
        .replace(/\n/g, '<br>')
        .replace(/<br><\/li>/g, '</li>'); // Limpieza para evitar <br> al final del <li>

    return html;
}

let contenido = @js($bloque['contenido'] ?? '');

// Reemplazar <br> y <br/> por saltos de línea reales (\n)
contenido = contenido
    .replace(/<br\s*\/?>/gi, '\n')
    .replace(/<\/p>\s*<p>/gi, '\n'); // por si hay párrafos seguidos

// ✅ Asignar el contenido procesado a Quill
quill.root.innerHTML = contenido;

    quill.on('text-change', () => {
    let html = quill.root.innerHTML;

    // 🧩 Reemplazar saltos de línea dentro de <li> por <br>
    html = html.replace(/(<li[^>]*>[^<]*)\n([^<]*<\/li>)/g, '$1<br>$2');

    // 🧩 Reemplazar saltos fuera de listas
    html = html.replace(/\n/g, '<br>');

    // 🧩 Limpieza para evitar <br> al final de listas o párrafos
    html = html
        .replace(/<br>\s*<\/li>/g, '</li>')
        .replace(/<br>\s*<\/p>/g, '</p>');

    // 🧠 Actualizar textarea y Livewire
        $refs.textarea{{ $i }}.value = quill.root.innerHTML;
        $refs.textarea{{ $i }}.dispatchEvent(new Event('input'));
});
                    };
                    init();
                ">
                    <div x-ref="editor{{ $i }}" style="height:180px;background:#fff;"></div>
                    <textarea x-ref="textarea{{ $i }}" wire:model="bloques.{{ $i }}.contenido" class="hidden"></textarea>
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

                            @if (!empty($img['src']))
                                <img src="{{ asset('storage/' . $img['src']) }}" class="w-32 h-32 object-cover rounded border mb-2">
                            @endif

                            <label class="block text-sm text-gray-700">Título</label>
                            <input type="text" wire:model="bloques.{{ $i }}.imagenes.{{ $j }}.leyenda"
                                class="mt-1 w-full border rounded px-2 py-1 text-sm"
                                placeholder="Ingrese título" style="border-color:rgba(31, 89, 177, 1);">

                            <label class="block text-sm text-gray-700 mt-3">Reemplazar imagen</label>
                            <input type="file" wire:model="bloques.{{ $i }}.imagenes.{{ $j }}.nuevo" accept="image/*" class="text-gray-500 border rounded p-1 cursor-pointer w-full text-sm" style="border-color:rgba(31, 89, 177, 1);">
                        </div>
                    @endforeach

                    <button type="button" wire:click="agregarImagen({{ $i }})"
                        class="text-blue-700 hover:underline text-sm">
                        + Agregar imagen
                    </button>
                </div>
            </div>
            <br>
        @endforeach
        <button type="button" wire:click="agregarBloque"
            class="px-4 py-2 text-white rounded bg-teal-600 hover:bg-teal-800 mb-4 ml-auto block">
            + Agregar bloque
        </button>

        {{-- Botón guardar --}}
        <div class="text-left pt-4">
            <button type="submit" class="px-6 py-2 text-white rounded bg-success border border-success hover:opacity-75">
                Guardar
            </button>
        </div>

        {{-- Mensaje de éxito --}}
        @if (session('mensaje'))
            <div class="text-center text-green-600 font-semibold mt-3">
                {{ session('mensaje') }}
            </div>
        @endif
    </form>

    {{-- Quill --}}
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
</div>
