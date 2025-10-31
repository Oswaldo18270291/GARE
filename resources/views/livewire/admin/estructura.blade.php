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
            @foreach ($titulos->where('status',1)->sortBy('orden') as $t)
                <li wire:key="title-{{ $t->id }}" class="p-3 border rounded bg-white" style="border-color:rgba(31, 89, 177, 1);">
                    <div class="flex gap-2 items-center flex-wrap">
                        <input type="text"
                            wire:model.defer="titleNames.{{ $t->id }}"
                            class="border rounded px-2 py-1 w-2/3">
                        <button wire:click="saveTitle({{ $t->id }})" class="px-3 py-1 bg-cyan-900 text-white rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                <title>Actualizar título</title>
                            </svg>
                        </button>
                        <button wire:click="addSubtitle({{ $t->id }})" class="px-3 py-1 bg-rose-900 text-white rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10.5v6m3-3H9m4.06-7.19-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                                <title>Agregar Subtitulo</title>
                            </svg>
                        </button>
                        <div class="flex gap-1">
                            <button wire:click="moveUpTitle({{ $t->id }})" class="px-2 text-gray-700 hover:text-blue-700" title="Mover arriba">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="33"
                                    height="33"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="#ffffffff"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="bg-blue-800 rounded"
                                    >
                                    <path d="M9 12h-3.586a1 1 0 0 1 -.707 -1.707l6.586 -6.586a1 1 0 0 1 1.414 0l6.586 6.586a1 1 0 0 1 -.707 1.707h-3.586v6h-6v-6z" />
                                    <path d="M9 21h6" />
                                </svg>
                            </button>
                            <button wire:click="moveDownTitle({{ $t->id }})" class="px-2 text-gray-700 hover:text-blue-700" title="Mover abajo">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="33"
                                    height="33"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="#ffffffff"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="bg-blue-800 rounded"
                                    >
                                    <path d="M15 12h3.586a1 1 0 0 1 .707 1.707l-6.586 6.586a1 1 0 0 1 -1.414 0l-6.586 -6.586a1 1 0 0 1 .707 -1.707h3.586v-6h6v6z" />
                                    <path d="M15 3h-6" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Subtítulos --}}
                    <ul class="pl-6 space-y-2 mt-2 border-l-2 border-blue-400">
                        @foreach ($t->subtitles->where('status',1)->sortBy('orden') as $st)
                            <li wire:key="subtitle-{{ $st->id }}" class="p-2 border rounded bg-gray-50">
                                <div class="flex gap-2 items-center flex-wrap">
                                    <input type="text" wire:model.defer="subtitleNames.{{ $st->id }}" class="border rounded px-2 py-1 w-2/3">
                                    <button wire:click="saveSubtitle({{ $st->id }})" class="px-3 py-1 bg-cyan-700 text-white rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                            <title>Actualizar Subtítulo</title>
                                        </svg>
                                    </button>
                                    <button wire:click="addSection({{ $st->id }})" class="px-3 py-1 bg-rose-700 text-white rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                            <title>Agregar Sección</title>
                                        </svg>
                                    </button>
                                    <div class="flex gap-1">
                                        <button wire:click="moveUpSubtitle({{ $st->id }})" class="px-2 text-gray-700 hover:text-blue-700">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="33"
                                                height="33"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="#ffffffff"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="bg-blue-600 rounded"
                                                >
                                                <path d="M9 12h-3.586a1 1 0 0 1 -.707 -1.707l6.586 -6.586a1 1 0 0 1 1.414 0l6.586 6.586a1 1 0 0 1 -.707 1.707h-3.586v6h-6v-6z" />
                                                <path d="M9 21h6" />
                                            </svg>
                                        </button>
                                        <button wire:click="moveDownSubtitle({{ $st->id }})" class="px-2 text-gray-700 hover:text-blue-700">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="33"
                                                height="33"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="#ffffffff"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="bg-blue-600 rounded"
                                                >
                                                <path d="M15 12h3.586a1 1 0 0 1 .707 1.707l-6.586 6.586a1 1 0 0 1 -1.414 0l-6.586 -6.586a1 1 0 0 1 .707 -1.707h3.586v-6h6v6z" />
                                                <path d="M15 3h-6" />
                                            </svg>
                                        </button>
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
                                    @foreach ($st->sections->where('status',1)->sortBy('orden') as $sec)
                                        <li wire:key="section-{{ $sec->id }}" class="p-2 border rounded bg-gray-100">
                                            <div class="flex gap-2 items-center flex-wrap">
                                                <input type="text" wire:model.defer="sectionNames.{{ $sec->id }}" class="border rounded px-2 py-1 w-2/3">
                                                <button wire:click="saveSection({{ $sec->id }})" class="px-3 py-1 bg-cyan-500 text-white rounded">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                                        <title>Actualizar Sección</title>
                                                    </svg>
                                                </button>
                                                <div class="flex gap-1">
                                                    <button wire:click="moveUpSection({{ $sec->id }})" class="px-2 text-gray-700 hover:text-blue-700">
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            width="33"
                                                            height="33"
                                                            viewBox="0 0 24 24"
                                                            fill="none"
                                                            stroke="#ffffffff"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="bg-blue-400 rounded"
                                                            >
                                                            <path d="M9 12h-3.586a1 1 0 0 1 -.707 -1.707l6.586 -6.586a1 1 0 0 1 1.414 0l6.586 6.586a1 1 0 0 1 -.707 1.707h-3.586v6h-6v-6z" />
                                                            <path d="M9 21h6" />
                                                        </svg>
                                                    </button>
                                                    <button wire:click="moveDownSection({{ $sec->id }})" class="px-2 text-gray-700 hover:text-blue-700">
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            width="33"
                                                            height="33"
                                                            viewBox="0 0 24 24"
                                                            fill="none"
                                                            stroke="#ffffffff"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="bg-blue-400 rounded"
                                                            >
                                                            <path d="M15 12h3.586a1 1 0 0 1 .707 1.707l-6.586 6.586a1 1 0 0 1 -1.414 0l-6.586 -6.586a1 1 0 0 1 .707 -1.707h3.586v-6h6v6z" />
                                                            <path d="M15 3h-6" />
                                                        </svg>
                                                    </button>
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
