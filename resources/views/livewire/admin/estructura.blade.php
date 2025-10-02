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
                                text-white 
                                hover:bg-emerald-800 hover:text-white 
                                disabled:bg-gray-400 disabled:text-gray-200 disabled:border-gray-400 disabled:cursor-not-allowed">Nuevo Título</button>

    {{-- Lista de títulos --}}
    <div class="w-full p-6 bg-surface-alt dark:bg-surface-dark-alt rounded-lg shadow-md">
        <ul class="space-y-3 mt-3">
            @foreach ($titulos->sortBy('id') as $t)
                <li wire:key="title-{{ $t->id }}" class="p-3 border rounded bg-white" style="border-color:rgba(31, 89, 177, 1);">
                    {{-- Input editable de título --}}
                    <div class="flex gap-2 items-center">
                        <input type="text"
                            wire:model.defer="titleNames.{{ $t->id }}"
                            class="border rounded px-2 py-1 w-2/3">
                        <button wire:click="saveTitle({{ $t->id }})" class="px-3 py-1 bg-blue-700 text-white rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                <path fill-rule="evenodd" d="M4.755 10.059a7.5 7.5 0 0 1 12.548-3.364l1.903 1.903h-3.183a.75.75 0 1 0 0 1.5h4.992a.75.75 0 0 0 .75-.75V4.356a.75.75 0 0 0-1.5 0v3.18l-1.9-1.9A9 9 0 0 0 3.306 9.67a.75.75 0 1 0 1.45.388Zm15.408 3.352a.75.75 0 0 0-.919.53 7.5 7.5 0 0 1-12.548 3.364l-1.902-1.903h3.183a.75.75 0 0 0 0-1.5H2.984a.75.75 0 0 0-.75.75v4.992a.75.75 0 0 0 1.5 0v-3.18l1.9 1.9a9 9 0 0 0 15.059-4.035.75.75 0 0 0-.53-.918Z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        {{-- Botón agregar subtítulo --}}
                        <button wire:click="addSubtitle({{ $t->id }})" class="px-3 py-1 text-white rounded" style="background-color: #a31f1f">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                <title>Agregar subtitulo</title>
                                <path fill-rule="evenodd" d="M19.5 21a3 3 0 0 0 3-3V9a3 3 0 0 0-3-3h-5.379a.75.75 0 0 1-.53-.22L11.47 3.66A2.25 2.25 0 0 0 9.879 3H4.5a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h15Zm-6.75-10.5a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25v2.25a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V10.5Z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>

                    {{-- Subtítulos --}}
                    <ul class="pl-6 space-y-2 mt-2">
                        <div class=" border" style="border-color:rgba(53, 118, 216, 1);">
                            @foreach ($t->subtitles->sortBy('id') as $st)
                                <li wire:key="subtitle-{{ $st->id }}" class="p-2 border rounded bg-gray-50">
                                    <div class="flex gap-2 items-center">
                                        <input type="text"
                                            wire:model.defer="subtitleNames.{{ $st->id }}"
                                            class="border rounded px-2 py-1 w-2/3">
                                        <button wire:click="saveSubtitle({{ $st->id }})" class="px-3 py-1 bg-blue-500 text-white rounded">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                                <path fill-rule="evenodd" d="M4.755 10.059a7.5 7.5 0 0 1 12.548-3.364l1.903 1.903h-3.183a.75.75 0 1 0 0 1.5h4.992a.75.75 0 0 0 .75-.75V4.356a.75.75 0 0 0-1.5 0v3.18l-1.9-1.9A9 9 0 0 0 3.306 9.67a.75.75 0 1 0 1.45.388Zm15.408 3.352a.75.75 0 0 0-.919.53 7.5 7.5 0 0 1-12.548 3.364l-1.902-1.903h3.183a.75.75 0 0 0 0-1.5H2.984a.75.75 0 0 0-.75.75v4.992a.75.75 0 0 0 1.5 0v-3.18l1.9 1.9a9 9 0 0 0 15.059-4.035.75.75 0 0 0-.53-.918Z" clip-rule="evenodd" />
                                            </svg>
                                        </button>

                                        {{-- Botón agregar sección --}}
                                        <button wire:click="addSection({{ $st->id }})" class="px-3 py-1 bg-purple-600 text-white rounded" style="background-color: #de5d5dff">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                                <title>Agregar sección</title>
                                                <path fill-rule="evenodd" d="M19.5 21a3 3 0 0 0 3-3V9a3 3 0 0 0-3-3h-5.379a.75.75 0 0 1-.53-.22L11.47 3.66A2.25 2.25 0 0 0 9.879 3H4.5a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h15Zm-6.75-10.5a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25v2.25a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V10.5Z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>

                                    {{-- Secciones --}}
                                    <ul class="pl-6 space-y-2 mt-2">
                                        @foreach ($st->sections->sortBy('id') as $sec)
                                            <li wire:key="section-{{ $sec->id }}" class="p-2 border rounded bg-gray-100">
                                                <div class="flex gap-2 items-center">
                                                    <input type="text"
                                                        wire:model.defer="sectionNames.{{ $sec->id }}"
                                                        class="border rounded px-2 py-1 w-2/3">
                                                    <button wire:click="saveSection({{ $sec->id }})" class="px-3 py-1 bg-blue-300 text-white rounded">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                                            <path fill-rule="evenodd" d="M4.755 10.059a7.5 7.5 0 0 1 12.548-3.364l1.903 1.903h-3.183a.75.75 0 1 0 0 1.5h4.992a.75.75 0 0 0 .75-.75V4.356a.75.75 0 0 0-1.5 0v3.18l-1.9-1.9A9 9 0 0 0 3.306 9.67a.75.75 0 1 0 1.45.388Zm15.408 3.352a.75.75 0 0 0-.919.53 7.5 7.5 0 0 1-12.548 3.364l-1.902-1.903h3.183a.75.75 0 0 0 0-1.5H2.984a.75.75 0 0 0-.75.75v4.992a.75.75 0 0 0 1.5 0v-3.18l1.9 1.9a9 9 0 0 0 15.059-4.035.75.75 0 0 0-.53-.918Z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </div>
                    </ul>
                </li>
            @endforeach
        </ul>
    </div>
</div>
