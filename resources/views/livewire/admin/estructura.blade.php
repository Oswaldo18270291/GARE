<div class="space-y-4">
    {{-- Mensaje --}}
    @if (session('success'))
        <div class="bg-green-200 text-green-800 px-4 py-2 rounded mb-3">
            {{ session('success') }}
        </div>
    @endif

    {{-- Botón agregar Título --}}
    <button wire:click="addTitle"
            class="px-3 py-1 border bg-emerald-600 rounded 
                    text-white hover:bg-emerald-800 hover:text-white 
                    disabled:bg-gray-400 disabled:text-gray-200 disabled:border-gray-400 disabled:cursor-not-allowed">
        Nuevo Título
    </button>

    {{-- Lista de títulos --}}
    <div class="w-full p-6 bg-surface-alt dark:bg-surface-dark-alt rounded-lg shadow-md">
        <ul class="space-y-3 mt-3">
            @foreach ($titulos->sortBy('orden') as $t)
                <li wire:key="title-{{ $t->id }}" class="p-3 border rounded bg-white" style="border-color:rgba(31, 89, 177, 1);">
                    <div class="flex gap-2 items-center flex-wrap">
                        <input type="text"
                            wire:model.defer="titleNames.{{ $t->id }}"
                            class="border rounded px-2 py-1 w-2/3">
                        <button wire:click="saveTitle({{ $t->id }})" class="px-3 py-1 bg-blue-700 text-white rounded">💾</button>
                        <button wire:click="addSubtitle({{ $t->id }})" class="px-3 py-1 bg-red-600 text-white rounded">➕ Subtítulo</button>
                        <div class="flex gap-1">
                            <button wire:click="moveUpTitle({{ $t->id }})" class="px-2 text-gray-700 hover:text-blue-700" title="Mover arriba">⬆️</button>
                            <button wire:click="moveDownTitle({{ $t->id }})" class="px-2 text-gray-700 hover:text-blue-700" title="Mover abajo">⬇️</button>
                        </div>
                    </div>

                    {{-- Subtítulos --}}
                    <ul class="pl-6 space-y-2 mt-2 border-l-2 border-blue-400">
                        @foreach ($t->subtitles->sortBy('orden') as $st)
                            <li wire:key="subtitle-{{ $st->id }}" class="p-2 border rounded bg-gray-50">
                                <div class="flex gap-2 items-center flex-wrap">
                                    <input type="text" wire:model.defer="subtitleNames.{{ $st->id }}" class="border rounded px-2 py-1 w-2/3">
                                    <button wire:click="saveSubtitle({{ $st->id }})" class="px-3 py-1 bg-blue-500 text-white rounded">💾</button>
                                    <button wire:click="addSection({{ $st->id }})" class="px-3 py-1 bg-red-500 text-white rounded">➕ Sección</button>
                                    <div class="flex gap-1">
                                        <button wire:click="moveUpSubtitle({{ $st->id }})" class="px-2 text-gray-700 hover:text-blue-700">⬆️</button>
                                        <button wire:click="moveDownSubtitle({{ $st->id }})" class="px-2 text-gray-700 hover:text-blue-700">⬇️</button>
                                    </div>

                                    {{-- Select para mover subtítulo a otro título --}}
                                    <select wire:change="moveSubtitleToTitle({{ $st->id }}, $event.target.value)"
                                            class="border rounded px-2 py-1 text-xs">
                                        <option value="">Mover a otro título...</option>
                                        @foreach ($titulos as $tituloPadre)
                                            @if ($tituloPadre->id !== $t->id)
                                                <option value="{{ $tituloPadre->id }}">{{ $tituloPadre->nombre }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Secciones --}}
                                <ul class="pl-6 space-y-2 mt-2 border-l-2 border-purple-400">
                                    @foreach ($st->sections->sortBy('orden') as $sec)
                                        <li wire:key="section-{{ $sec->id }}" class="p-2 border rounded bg-gray-100">
                                            <div class="flex gap-2 items-center flex-wrap">
                                                <input type="text" wire:model.defer="sectionNames.{{ $sec->id }}" class="border rounded px-2 py-1 w-2/3">
                                                <button wire:click="saveSection({{ $sec->id }})" class="px-3 py-1 bg-blue-300 text-white rounded">💾</button>
                                                <div class="flex gap-1">
                                                    <button wire:click="moveUpSection({{ $sec->id }})" class="px-2 text-gray-700 hover:text-blue-700">⬆️</button>
                                                    <button wire:click="moveDownSection({{ $sec->id }})" class="px-2 text-gray-700 hover:text-blue-700">⬇️</button>
                                                </div>

                                                {{-- Select para mover sección a otro subtítulo --}}
                                                <select wire:change="moveSectionToSubtitle({{ $sec->id }}, $event.target.value)"
                                                        class="border rounded px-2 py-1 text-xs">
                                                    <option value="">Mover a otro subtítulo...</option>
                                                    @foreach ($t->subtitles as $subPadre)
                                                        @if ($subPadre->id !== $st->id)
                                                            <option value="{{ $subPadre->id }}">{{ $subPadre->nombre }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
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
</div>
