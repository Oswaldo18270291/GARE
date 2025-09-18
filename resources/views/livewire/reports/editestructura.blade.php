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
            <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                <label for="textInputDefault" class="w-fit pl-0.5 text-2x1">Colaborador</label>
                <input value="{{$report->colaborador1}}" required wire:model="colaborador" id="colaborador" type="text" class="bg-white w-full rounded-radius border border-outline bg-surface-alt px-2 py-2
                text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed 
                disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark" name="colaborador" 
                placeholder="Ingrese Ubicación de la empresa" autocomplete="name" style="border-color:rgba(31, 89, 177, 1);"/>
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
                                x-ref="logoInput"
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
            </div>
        </div>
        <br>
    </div>