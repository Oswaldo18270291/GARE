<div class="space-y-4">
    {{-- Títulos --}}
    <ul class="space-y-3">
        @foreach ($tree->sortBy('id') as $t)
            <li wire:key="title-{{ $t->id }}" class="p-3 border rounded bg-white">
                <div class="flex gap-2 items-center">
                    <input type="text"
                           wire:model.defer="titleNames.{{ $t->id }}"
                           class="border rounded px-2 py-1 w-2/3">
                    <button wire:click="saveTitle({{ $t->id }})"
                            class="px-3 py-1 bg-blue-600 text-white rounded">💾</button>
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
