<div>
    <!-- Bloque Quill -->
    <div x-data wire:ignore 
        x-init="$nextTick(() => {
            const quill = new Quill($refs.editorTit, {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ header: [1, 2, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ 'align': [] }],
                        [{ list: 'ordered'}, { list: 'bullet' }],
                        ['clean']
                    ]
                }
            });

            quill.root.innerHTML = @js($contenido);

            quill.on('text-change', function () {
                $refs.textareaTit.value = quill.root.innerHTML;
                $refs.textareaTit.dispatchEvent(new Event('input'));
            });
        })"
    >
        <div x-ref="editorTit" style="height:200px;"></div>
        <textarea x-ref="textareaTit" wire:model="contenido" class="hidden"></textarea>
    </div>

    <br>

<div class="flex gap-6 flex-wrap">
    <div class="flex flex-col items-center w-full max-w-xs">
        <div 
                        class="flex w-full max-w-xl text-center flex-col gap-1"
                        x-data="{
                            isDropping: false,
                            errorMsg: '',
                            handleFile(file, field, input) {
                                this.errorMsg = '';
                                if (file && file.type.startsWith('image/')) {
                                    $wire.upload(field, file, 
                                        () => {}, 
                                        () => { this.errorMsg = '⚠️ Error al subir la imagen.' }
                                    );
                                } else {
                                    this.errorMsg = '⚠️ Solo se permiten imágenes PNG o JPG.';
                                    input.value = ''; // limpia el input
                                }
                            },
                            handleDrop(e, field, input) {
                                this.isDropping = false;
                                const file = e.dataTransfer.files[0];
                                this.handleFile(file, field, input);
                            }
                        }"
                        x-on:drop.prevent="handleDrop($event, 'img1', $refs.img1Input)"
                        x-on:dragover.prevent="isDropping = true"
                        x-on:dragleave.prevent="isDropping = false"
                    >
                        <span class="w-fit pl-0.5 text-2x1">Imagen 1</span>

                        {{-- Contenedor Drag & Drop --}}
                        <div 
                            class="bg-white flex w-full flex-col items-center justify-center gap-2 rounded-radius border border-dashed p-8 transition"
                            :class="isDropping ? 'border-primary bg-primary/5' : 'border-gray-300'"
                            style="border-color:rgba(31, 89, 177, 1);"
                        >
                            {{-- Input oculto --}}
                            <input 
                                id="img1" 
                                type="file" 
                                class="sr-only" 
                                accept="image/png,image/jpeg"
                                x-ref="img1Input"
                                x-on:change="handleFile($event.target.files[0], 'img1', $event.target)" 
                            />

                            <label for="img1" class="cursor-pointer font-medium text-primary">
                                Arrastra o carga tu imagen aquí
                            </label>
                            <small>PNG, JPG - Max 5MB</small>

                            {{-- Vista previa imagen actual (si existe y no se ha subido una nueva) --}}
                            @if ($content && $content->img1 && !$img1)
                                <img src="{{ asset('storage/' . $content->img1) }}" 
                                    class="w-32 h-32 object-cover mt-2 rounded border" 
                                    alt="Imagen actual" />
                            @endif

                            {{-- Vista previa nueva imagen --}}
                            @if ($img1)
                                <img src="{{ $img1->temporaryUrl() }}" 
                                    class="w-32 h-32 object-cover mt-2 rounded border" 
                                    alt="Vista previa nueva" />
                            @endif

                            {{-- Mensajes de error --}}
                            <p x-show="errorMsg" x-text="errorMsg" class="text-red-600 text-sm mt-2"></p>
                            @error('img1')
                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
    <div class="flex w-full flex-col gap-1 mt-2">            
        <label class="w-fit pl-0.5 text-2x1">Leyenda 1</label>
        <input wire:model="leyenda1" id="leyenda1" type="text"
               class="bg-white w-full rounded-radius border border-outline px-2 py-2 text-sm"
               placeholder="Ingrese texto de leyenda 1"
               style="border-color:rgba(31, 89, 177, 1);" />
            @error('leyenda1') 
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
    </div>
</div>

        <!-- Imagen 2 -->
       <div class="flex flex-col items-center w-full max-w-xs">
        <div 
                        class="flex w-full max-w-xl text-center flex-col gap-1"
                        x-data="{
                            isDropping: false,
                            errorMsg: '',
                            handleFile(file, field, input) {
                                this.errorMsg = '';
                                if (file && file.type.startsWith('image/')) {
                                    $wire.upload(field, file, 
                                        () => {}, 
                                        () => { this.errorMsg = '⚠️ Error al subir la imagen.' }
                                    );
                                } else {
                                    this.errorMsg = '⚠️ Solo se permiten imágenes PNG o JPG.';
                                    input.value = ''; // limpia el input
                                }
                            },
                            handleDrop(e, field, input) {
                                this.isDropping = false;
                                const file = e.dataTransfer.files[0];
                                this.handleFile(file, field, input);
                            }
                        }"
                        x-on:drop.prevent="handleDrop($event, 'img2', $refs.img2Input)"
                        x-on:dragover.prevent="isDropping = true"
                        x-on:dragleave.prevent="isDropping = false"
                    >
                        <span class="w-fit pl-0.5 text-2x1">Imagen 2</span>

                        {{-- Contenedor Drag & Drop --}}
                        <div 
                            class="bg-white flex w-full flex-col items-center justify-center gap-2 rounded-radius border border-dashed p-8 transition"
                            :class="isDropping ? 'border-primary bg-primary/5' : 'border-gray-300'"
                            style="border-color:rgba(31, 89, 177, 1);"
                        >
                            {{-- Input oculto --}}
                            <input 
                                id="img2" 
                                type="file" 
                                class="sr-only" 
                                accept="image/png,image/jpeg"
                                x-ref="img2Input"
                                x-on:change="handleFile($event.target.files[0], 'img2', $event.target)" 
                            />

                            <label for="img2" class="cursor-pointer font-medium text-primary">
                                Arrastra o carga tu imagen aquí
                            </label>
                            <small>PNG, JPG - Max 5MB</small>

                            {{-- Vista previa imagen actual (si existe y no se ha subido una nueva) --}}
                            @if ($content && $content->img2 && !$img2)
                                <img src="{{ asset('storage/' . $content->img2) }}" 
                                    class="w-32 h-32 object-cover mt-2 rounded border" 
                                    alt="Imagen actual" />
                            @endif

                            {{-- Vista previa nueva imagen --}}
                            @if ($img2)
                                <img src="{{ $img2->temporaryUrl() }}" 
                                    class="w-32 h-32 object-cover mt-2 rounded border" 
                                    alt="Vista previa nueva" />
                            @endif

                            {{-- Mensajes de error --}}
                            <p x-show="errorMsg" x-text="errorMsg" class="text-red-600 text-sm mt-2"></p>
                            @error('img2')
                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
    <div class="flex w-full flex-col gap-1 mt-2">            
        <label class="w-fit pl-0.5 text-2x1">Leyenda 2</label>
        <input wire:model="leyenda2" id="leyenda2" type="text"
               class="bg-white w-full rounded-radius border border-outline px-2 py-2 text-sm"
               placeholder="Ingrese texto de leyenda 1"
               style="border-color:rgba(31, 89, 177, 1);" />
            @error('leyenda2') 
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
    </div>
</div>
        <!-- Imagen 3 -->
        <div class="flex flex-col items-center w-full max-w-xs">
        <div 
                        class="flex w-full max-w-xl text-center flex-col gap-1"
                        x-data="{
                            isDropping: false,
                            errorMsg: '',
                            handleFile(file, field, input) {
                                this.errorMsg = '';
                                if (file && file.type.startsWith('image/')) {
                                    $wire.upload(field, file, 
                                        () => {}, 
                                        () => { this.errorMsg = '⚠️ Error al subir la imagen.' }
                                    );
                                } else {
                                    this.errorMsg = '⚠️ Solo se permiten imágenes PNG o JPG.';
                                    input.value = ''; // limpia el input
                                }
                            },
                            handleDrop(e, field, input) {
                                this.isDropping = false;
                                const file = e.dataTransfer.files[0];
                                this.handleFile(file, field, input);
                            }
                        }"
                        x-on:drop.prevent="handleDrop($event, 'img3', $refs.img3Input)"
                        x-on:dragover.prevent="isDropping = true"
                        x-on:dragleave.prevent="isDropping = false"
                    >
                        <span class="w-fit pl-0.5 text-2x1">Imagen 3</span>

                        {{-- Contenedor Drag & Drop --}}
                        <div 
                            class="bg-white flex w-full flex-col items-center justify-center gap-2 rounded-radius border border-dashed p-8 transition"
                            :class="isDropping ? 'border-primary bg-primary/5' : 'border-gray-300'"
                            style="border-color:rgba(31, 89, 177, 1);"
                        >
                            {{-- Input oculto --}}
                            <input 
                                id="img3" 
                                type="file" 
                                class="sr-only" 
                                accept="image/png,image/jpeg"
                                x-ref="img3Input"
                                x-on:change="handleFile($event.target.files[0], 'img3', $event.target)" 
                            />

                            <label for="img3" class="cursor-pointer font-medium text-primary">
                                Arrastra o carga tu imagen aquí
                            </label>
                            <small>PNG, JPG - Max 5MB</small>

                            {{-- Vista previa imagen actual (si existe y no se ha subido una nueva) --}}
                            @if ($content && $content->img3 && !$img3)
                                <img src="{{ asset('storage/' . $content->img3) }}" 
                                    class="w-32 h-32 object-cover mt-2 rounded border" 
                                    alt="Imagen actual" />
                            @endif

                            {{-- Vista previa nueva imagen --}}
                            @if ($img3)
                                <img src="{{ $img3->temporaryUrl() }}" 
                                    class="w-32 h-32 object-cover mt-2 rounded border" 
                                    alt="Vista previa nueva" />
                            @endif

                            {{-- Mensajes de error --}}
                            <p x-show="errorMsg" x-text="errorMsg" class="text-red-600 text-sm mt-2"></p>
                            @error('img3')
                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                <div class="flex w-full flex-col gap-1 mt-2">            
                    <label class="w-fit pl-0.5 text-2x1">Leyenda 3</label>
                    <input wire:model="leyenda3" id="leyenda3" type="text"
                        class="bg-white w-full rounded-radius border border-outline px-2 py-2 text-sm"
                        placeholder="Ingrese texto de leyenda 1"
                        style="border-color:rgba(31, 89, 177, 1);" />
                        @error('leyenda3') 
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                </div>
</div>
</div>
    <br>

    <button type="submit"
        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        💾 Guardar Cambios
    </button>
</div>
