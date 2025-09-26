<div>
a
    @foreach ($titles as $t)
                <div class="title-wrapper">
                    <label>
                        <input
                            value="{{ $t->id }}"
                            id="title_{{ $t->id }}"
                            wire:model="title"
                            type="checkbox"
                            class="toggle-subtitles"
                        />
                        <strong>{{ $t->nombre }}</strong>
                    </label>

                    <div class="subtitles"
                        style="{{ ($expandAll || in_array($t->id, $title ?? [])) 
                                ? 'display:block; margin-left:20px;' 
                                : 'display:none; margin-left:20px;' }}">
                        
                        @foreach ($t->subtitles as $st)
                            <div class="subtitle-wrapper">
                                <label>
                                    <input
                                        value="{{ $st->id }}"
                                        id="subtitle_{{ $st->id }}"
                                        wire:model="subtitle"
                                        type="checkbox"
                                        class="toggle-sections"
                                    />
                                    {{ $st->nombre }}
                                </label>

                                <ul class="sections"
                                    style="{{ ($expandAll || in_array($st->id, $subtitle ?? [])) 
                                            ? 'display:block; margin-left:20px;' 
                                            : 'display:none; margin-left:20px;' }}">
                                    @foreach ($st->sections as $sec)
                                        <li>
                                            <label>
                                                <input
                                                    value="{{ $sec->id }}"
                                                    id="section_{{ $sec->id }}"
                                                    wire:model="section"
                                                    type="checkbox"
                                                />
                                                {{ $sec->nombre }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
</div>
