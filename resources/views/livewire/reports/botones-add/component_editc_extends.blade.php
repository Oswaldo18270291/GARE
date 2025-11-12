
<div>
<div class="mt-6">
    <h3 class="font-bold text-base text-blue-900 mb-2">COTIZACIONES DE SISTEMAS TECNOLÓGICOS</h3>

    <div class="flex justify-end mb-3">
        <button type="button" wire:click="agregarEmpresa"
            class="bg-blue-700 hover:bg-blue-800 text-white font-semibold px-4 py-2 rounded">
            + Agregar Empresa
        </button>
    </div>

    <table class="w-full border-collapse text-sm" style="border:1px solid #001a4d;">
        <thead class="bg-[#002060] text-white text-center">
            <tr>
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
                        @if ($iIndex == 0)
                            <td rowspan="{{ count($empresa['items']) }}" class="border p-2 align-top font-semibold">
                                <input type="text" wire:model="empresas.{{ $eIndex }}.nombre"
                                    placeholder="Nombre de empresa" class="w-full border rounded text-xs p-1 mb-2">

                                <input type="color" wire:model="empresas.{{ $eIndex }}.color"
                                    class="w-6 h-6 cursor-pointer mb-2">

                                <div class="flex flex-col gap-1">
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

                        <td class="border p-1">
                            <input type="text" wire:model="empresas.{{ $eIndex }}.items.{{ $iIndex }}.concepto"
                                placeholder="Concepto" class="w-full text-xs border rounded p-1">
                        </td>
                        <td class="border p-1 text-center">
                            <input type="text" wire:model="empresas.{{ $eIndex }}.items.{{ $iIndex }}.cantidad"
                                class="w-16 text-xs border rounded p-1 text-center">
                        </td>
                        <td class="border p-1 text-center">
                            <input type="text" wire:model="empresas.{{ $eIndex }}.items.{{ $iIndex }}.costo"
                                class="w-32 text-xs border rounded p-1 text-center">
                        </td>
                        <td class="border p-1 text-center">
                            <input type="text" wire:model="empresas.{{ $eIndex }}.items.{{ $iIndex }}.comentarios"
                                class="w-full text-xs border rounded p-1 text-center mb-1">
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
                        No hay empresas registradas.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

    <div class="mb-6 flex items-center justify-center p-4 rounded-lg bg-[#274470]">
        <h1 class="text-white font-sans font-extrabold text-xl">
            ✏️ Editar bloques existentes
        </h1>
    </div>

    <form wire:submit.prevent="update" class="space-y-6">

        @foreach ($bloques as $i => $bloque)
            <div class="border rounded-lg p-4 bg-white shadow space-y-4">
                <h2 class="font-semibold text-blue-800">Bloque {{ $i + 1 }}</h2>

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
                                    ['clean']
                                ]
                            }
                        });
                        quill.root.innerHTML = @js($bloque['contenido']);
                        quill.on('text-change', () => {
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
                        <div class="border p-3 rounded-lg bg-gray-50">
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="font-medium text-gray-700">Imagen {{ $j + 1 }}</h3>
                                <button type="button" wire:click="eliminarImagen({{ $i }}, {{ $j }})"
                                    class="text-sm text-red-600 hover:underline">🗑 Quitar</button>
                            </div>

                            @if ($img['src'])
                                <img src="{{ asset('storage/' . $img['src']) }}" class="w-32 h-32 object-cover rounded border mb-2">
                            @endif

                            <label class="block text-sm text-gray-700">Título</label>
                            <input type="text" wire:model="bloques.{{ $i }}.imagenes.{{ $j }}.leyenda"
                                class="mt-1 w-full border rounded px-2 py-1 text-sm"
                                placeholder="Ingrese título">

                            <label class="block text-sm text-gray-700 mt-3">Reemplazar imagen</label>
                            <input type="file" wire:model="bloques.{{ $i }}.imagenes.{{ $j }}.nuevo" accept="image/*">
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
            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                💾 Guardar todos los cambios
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
