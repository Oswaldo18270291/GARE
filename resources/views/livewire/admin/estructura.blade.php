<div class="space-y-4">
    {{-- Mensaje --}}
    @if (session('success'))
        <div class="bg-green-200 text-green-800 px-4 py-2 rounded mb-3">
            {{ session('success') }}
        </div>
    @endif

    {{-- Botón agregar Título --}}
    <button wire:click="addTitle"
            class="px-3 py-1 bg-blue-700 text-white rounded">➕ Nuevo Título</button>

    {{-- Lista de títulos --}}
    <ul class="space-y-3 mt-3">
        @foreach ($titulos->sortBy('id') as $t)
            <li wire:key="title-{{ $t->id }}" class="p-3 border rounded bg-white">
                {{-- Input editable de título --}}
                <div class="flex gap-2 items-center">
                    <input type="text"
                           wire:model.defer="titleNames.{{ $t->id }}"
                           class="border rounded px-2 py-1 w-2/3">
                    <button wire:click="saveTitle({{ $t->id }})"
                            class="px-3 py-1 bg-blue-600 text-white rounded">💾</button>

                    {{-- Botón agregar subtítulo --}}
                    <button wire:click="addSubtitle({{ $t->id }})"
                            class="px-3 py-1 bg-green-600 text-white rounded">➕ Subtítulo</button>
                </div>

                {{-- Subtítulos --}}
                <ul class="pl-6 space-y-2 mt-2">
                    @foreach ($t->subtitles->sortBy('id') as $st)
                        <li wire:key="subtitle-{{ $st->id }}" class="p-2 border rounded bg-gray-50">
                            <div class="flex gap-2 items-center">
                                <input type="text"
                                       wire:model.defer="subtitleNames.{{ $st->id }}"
                                       class="border rounded px-2 py-1 w-2/3">
                                <button wire:click="saveSubtitle({{ $st->id }})"
                                        class="px-3 py-1 bg-green-600 text-white rounded">💾</button>

                                {{-- Botón agregar sección --}}
                                <button wire:click="addSection({{ $st->id }})"
                                        class="px-3 py-1 bg-purple-600 text-white rounded">➕ Sección</button>
                            </div>

                            {{-- Secciones --}}
                            <ul class="pl-6 space-y-2 mt-2">
                                @foreach ($st->sections->sortBy('id') as $sec)
                                    <li wire:key="section-{{ $sec->id }}" class="p-2 border rounded bg-gray-100">
                                        <div class="flex gap-2 items-center">
                                            <input type="text"
                                                   wire:model.defer="sectionNames.{{ $sec->id }}"
                                                   class="border rounded px-2 py-1 w-2/3">
                                            <button wire:click="saveSection({{ $sec->id }})"
                                                    class="px-3 py-1 bg-purple-600 text-white rounded">💾</button>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
</div>
