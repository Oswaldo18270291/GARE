<form  wire:submit='update' class="w-full p-6 bg-surface-alt dark:bg-surface-dark-alt rounded-lg shadow-md">
<div>
        <div class="mb-6 flex items-center justify-center p-4 rounded-lg" style="background-color: rgba(39, 68, 112, 1);">
            <h1 class=" text-white font-sans font-bond text-lg">MODIFICACIÓN DE INFORME</h1>
        </div>
        <div class="flex w-full max-w gap-4 text-on-surface dark:text-on-surface-dark">
        <div>
            <div class="flex w-full max-w-2xl gap-4 text-on-surface dark:text-on-surface-dark">
                <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                    <label for="textInputDefault" class="w-fit pl-0.5 text-2x1">Nombre de empresa</label>
                    <input value="{{$report->nombre_empresa}}" required wire:model="nombre_empresa" id="nombre_empresa" type="text" class="bg-white w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 
                    text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed 
                    disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark" name="nombre_empresa" 
                    placeholder="Ingrese Nombre de empresa" autocomplete="name" style="border-color:rgba(31, 89, 177, 1);"/>
                </div>
                <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                    <label for="textInputDefault" class="w-fit pl-0.5 text-2x1">Giro de empresa</label>
                    <input value="{{$report->giro_empresa}}" required wire:model="giro_empresa" id="giro_empresa" type="text" class=" bg-white w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 
                    text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed 
                    disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark" name="giro_empresa" 
                    placeholder="Ingrese Giro de empresa" autocomplete="name" style="border-color:rgba(31, 89, 177, 1);"/>
                </div>
            </div>
            <br>
            <div class="flex w-full max-w-2xl gap-4 text-on-surface dark:text-on-surface-dark">
                <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                    <label for="textInputDefault" class="w-fit pl-0.5 text-2x1">Ubicación</label>
                    <input  value="{{$report->ubicacion}}" wire:model="ubicacion" id="ubicacion" type="text" class="bg-white w-full rounded-radius border border-outline bg-surface-alt px-2 py-2
                    text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed 
                    disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark" name="ubicacion" 
                    placeholder="Ingrese Ubicación de la empresa" autocomplete="name" style="border-color:rgba(31, 89, 177, 1);"/>
                </div>
                <div x-data class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                    <label for="phoneInput" class="w-fit pl-0.5 text-2x1">Telefono</label>
                    <input  value="{{$report->telefono}}" wire:model="telefono" id="telefono" type="text" class="bg-white w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 text-sm 
                    focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 
                    dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark" x-mask="(999) 999-9999" 
                    name="telefono" autocomplete="tel-national" placeholder="(999) 999-9999" style="border-color:rgba(31, 89, 177, 1);"/>
                </div>
            </div>
            <br>
            <div class="flex w-full max-w-2xl gap-4 text-on-surface dark:text-on-surface-dark">
                <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                    <label for="textInputDefault" class="w-fit pl-0.5 text-2x1">Representante</label>
                    <input value="{{$report->representante}}" required wire:model="representante" id="representante" type="text" class="bg-white w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 
                    text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed 
                    disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark" name="representante" 
                    placeholder="Ingrese representantes" autocomplete="name" style="border-color:rgba(31, 89, 177, 1);"/>
                </div>
                <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                    <label for="fecha_analisis" class="w-fit pl-0.5 text-2x1">Fecha de análisis</label>
                    <input value="{{$report->fecha_analisis}}" required wire:model="fecha_analisis" id="fecha_analisis" type="date" name="fecha_analisis" class="bg-white w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 
                    text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary 
                    disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                    style="border-color:rgba(31, 89, 177, 1);" />
                </div>    
            </div>
            <br>
            <div class="flex w-full max-w-2xl gap-4 text-on-surface dark:text-on-surface-dark">
                <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                    <label for="textInputDefault" class="w-fit pl-0.5 text-2x1">Colaborador</label>
                    <input value="{{$report->colaborador1}}" required wire:model="colaborador" id="colaborador" type="text" class="bg-white w-full rounded-radius border border-outline bg-surface-alt px-2 py-2
                    text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed 
                    disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark" name="colaborador" 
                    placeholder="Ingrese Ubicación de la empresa" autocomplete="name" style="border-color:rgba(31, 89, 177, 1);"/>
                </div>
                <div class="relative flex w-full max-w-xs flex-col gap-1">
                    <label for="clasificacion" class="w-fit pl-0.5 text-sm text-gray-700">Tipo de clasificación:</label>

                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        class="absolute pointer-events-none right-4 top-8 size-5 text-gray-600">
                        <path fill-rule="evenodd"
                            d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                            clip-rule="evenodd" />
                    </svg>

                    <select
                        wire:model="clasificacion"
                        id="clasificacion"
                        name="clasificacion"
                        class="w-full appearance-none rounded-md border border-blue-700 bg-white text-black px-4 py-2 text-sm 
                            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        style="background-color: white; color: black;"
                    >
                        <option value="" select disabled>Seleccione una opción</option>
                        <option value="Público">Público</option>
                        <option value="Confidencial">Confidencial</option>
                    </select>
                </div>
            </div>
            <br>
                <div class="flex w-full max-w-2xl gap-4 text-on-surface dark:text-on-surface-dark">
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
                        x-on:drop.prevent="handleDrop($event, 'img', $refs.imgInput)"
                        x-on:dragover.prevent="isDropping = true"
                        x-on:dragleave.prevent="isDropping = false"
                    >
                        <span class="w-fit pl-0.5 text-2x1">Importa la imagen de la portada</span>

                        {{-- Contenedor Drag & Drop --}}
                        <div 
                            class="bg-white flex w-full flex-col items-center justify-center gap-2 rounded-radius border border-dashed p-8 transition"
                            :class="isDropping ? 'border-primary bg-primary/5' : 'border-gray-300'"
                            style="border-color:rgba(31, 89, 177, 1);"
                        >
                            {{-- Input oculto --}}
                            <input 
                                id="img" 
                                type="file" 
                                class="sr-only" 
                                accept="image/png,image/jpeg"
                                x-ref="imgInput"
                                x-on:change="handleFile($event.target.files[0], 'img', $event.target)" 
                            />

                            <label for="img" class="cursor-pointer font-medium text-primary">
                                Arrastra o carga tu imagen aquí
                            </label>
                            <small>PNG, JPG - Max 5MB</small>

                            {{-- Vista previa imagen actual (si existe y no se ha subido una nueva) --}}
                            @if ($report && $report->img && !$img)
                                <img src="{{ asset('storage/' . $report->img) }}" 
                                    class="w-32 h-32 object-cover mt-2 rounded border" 
                                    alt="Imagen actual" />
                            @endif

                            {{-- Vista previa nueva imagen --}}
                            @if ($img)
                                <img src="{{ $img->temporaryUrl() }}" 
                                    class="w-32 h-32 object-cover mt-2 rounded border" 
                                    alt="Vista previa nueva" />
                            @endif

                            {{-- Mensajes de error --}}
                            <p x-show="errorMsg" x-text="errorMsg" class="text-red-600 text-sm mt-2"></p>
                            @error('img')
                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                                {{-- Logo empresa --}}
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
                                        () => { this.errorMsg = '⚠️ Error al subir el logo.' }
                                    );
                                } else {
                                    this.errorMsg = '⚠️ Solo se permiten imágenes PNG o JPG.';
                                    input.value = '';
                                }
                            },
                            handleDrop(e, field, input) {
                                this.isDropping = false;
                                const file = e.dataTransfer.files[0];
                                this.handleFile(file, field, input);
                            }
                        }"
                        x-on:drop.prevent="handleDrop($event, 'logo', $refs.logoInput)"
                        x-on:dragover.prevent="isDropping = true"
                        x-on:dragleave.prevent="isDropping = false"
                    >
                        <span class="w-fit pl-0.5 text-2x1">Importa el logo de la empresa</span>

                        <div 
                            class="bg-white flex w-full flex-col items-center justify-center gap-2 rounded-radius border border-dashed p-8 transition"
                            :class="isDropping ? 'border-primary bg-primary/5' : 'border-gray-300'"
                            style="border-color:rgba(31, 89, 177, 1);"
                        >
                            {{-- Input oculto --}}
                            <input 
                                id="logo" 
                                type="file" 
                                class="sr-only" 
                                accept="image/png,image/jpeg"
                                x-ref="imgInput"
                                x-on:change="handleFile($event.target.files[0], 'logo', $event.target)" 
                            />

                            <label for="logo" class="cursor-pointer font-medium text-primary">
                                Arrastra o carga tu imagen aquí
                            </label>
                            <small>PNG, JPG - Max 5MB</small>

                            {{-- Vista previa logo actual (si existe en BD y no hay nuevo cargado) --}}
                            @if ($report && $report->logo && !$logo)
                                <img src="{{ asset('storage/' . $report->logo) }}" 
                                    class="w-32 h-32 object-cover mt-2 rounded border" 
                                    alt="Logo actual" />
                            @endif

                            {{-- Vista previa nuevo logo --}}
                            @if ($logo)
                                <img src="{{ $logo->temporaryUrl() }}" 
                                    class="w-32 h-32 object-cover mt-2 rounded border" 
                                    alt="Vista previa nuevo logo" />
                            @endif

                            {{-- Mensajes de error --}}
                            <p x-show="errorMsg" x-text="errorMsg" class="text-red-600 text-sm mt-2"></p>
                            @error('logo')
                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <hr class="my-6 border-t border-gray-300">

<div class="flex flex-col gap-4 p-4 bg-white rounded border" style="border-color:rgba(31, 89, 177, 1);">
    <span class="text-lg font-semibold text-center" style="color:#0f4a75;">Selecciona una portada</span>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 place-items-center">
        @for ($i = 1; $i <= 5; $i++)
            <label class="relative cursor-pointer group" wire:key="portada-{{ $i }}">
                <input 
                    type="radio"
                    wire:model="img_portada"
                    value="{{ 'img_portada/portada' . $i . '.png' }}"
                    class="hidden peer"
                />

                <img 
                    src="{{ asset('img_portada/portada' . $i . '.png') }}"
                    alt="Portada {{ $i }}" 
                    class="w-32 h-44 object-cover rounded-lg border-4 transition-all 
                    peer-checked:border-blue-600 peer-checked:ring-4 peer-checked:ring-blue-300 hover:scale-105"
                />

                <span class="absolute bottom-2 left-1/2 -translate-x-1/2 text-xs px-2 py-1 rounded bg-black/60 text-white opacity-0 group-hover:opacity-100 transition">
                    Seleccionar
                </span>

                <svg xmlns="http://www.w3.org/2000/svg" 
                    class="absolute right-2 top-2 w-6 h-6 text-green-500 bg-white rounded-full p-1 
                    opacity-0 peer-checked:opacity-100 transition"
                    fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" 
                        d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14Zm3.844-8.791a.75.75 0 0 0-1.188-.918l-3.7 
                        4.79-1.649-1.833a.75.75 0 1 0-1.114 
                        1.004l2.25 2.5a.75.75 0 0 0 
                        1.15-.043l4.25-5.5Z"
                        clip-rule="evenodd"/>
                </svg>
            </label>
        @endfor
    </div>
</div>
            </div>
            
            <div class="bg-white overflow-y-auto max-h-[600px] p-4 border rounded" style="border-color:rgba(31, 89, 177, 1);">
                <div class="font-sans text-lg mb-4 text-center" style="background-color: rgba(143, 6, 6, 1); color: white; padding: 8px; border-radius: 8px;">
                    <label for="textInputDefault" class="w-fit pl-0.5 text-2x1">Esquema de Informe</label>
                </div>

              @foreach ($titlesCatalog as $t)
                <div class="title-wrapper" x-data="{ isOpen: @js(in_array($t->id, $titles ?? [])) }">
                    <label>
                    <input
                        type="checkbox"
                        value="{{ $t->id }}"
                        wire:model="titles"
                        x-on:click="isOpen = $event.target.checked"
                    />
                    <strong>{{ $t->nombre }}</strong>
                    </label>

                    <div class="subtitles" x-show="isOpen" style="margin-left:20px;">
                    @foreach ($t->subtitles as $st)
                        <div class="subtitle-wrapper" x-data="{ isSubOpen: @js(in_array($st->id, $subtitles ?? [])) }">
                        <label>
                            <input
                            type="checkbox"
                            value="{{ $st->id }}"
                            wire:model="subtitles"
                            class="subtitle-checkbox"
                            x-on:click="isSubOpen = $event.target.checked"
                            />
                            {{ $st->nombre }}
                        </label>

                        <ul class="sections" x-show="isSubOpen" style="margin-left:20px;">
                            @foreach ($st->sections as $sec)
                            <li>
                                <label>
                                <input
                                    type="checkbox"
                                    value="{{ $sec->id }}"
                                    wire:model="sections"
                                    class="section-checkbox"
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
    </div>
        <br>
        <button type="submit" class="inline-flex justify-center items-center gap-2 whitespace-nowrap rounded-radius bg-success border border-success dark:border-success px-4 py-2 text-sm font-medium tracking-wide text-on-success transition hover:opacity-75 text-center focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-success active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-success dark:text-on-success dark:focus-visible:outline-success">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>
            Actualizar datos
        </button>
    </div>
</form>