<form  wire:submit='store' class="space-y-4 w-full p-6 bg-surface-alt dark:bg-surface-dark-alt rounded-lg shadow-md">
<div>
    <div class="mb-4 flex items-center justify-center p-4 rounded-lg" style="background-color: rgba(39, 68, 112, 1);">
        <h1 class=" text-white font-serif font-bond text-lg">CREACIÓN DE REPORTES</h1>
    </div>

    <div class="flex w-full max-w-2xl gap-4 text-on-surface dark:text-on-surface-dark">
        <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
            <label for="textInputDefault" class="w-fit pl-0.5 text-2x1">Nombre de empresa</label>
            <input required wire:model="nombre_empresa" id="nombre_empresa" type="text" class="w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 
            text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed 
            disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark" name="nombre_empresa" 
            placeholder="Ingrese Nombre de empresa" autocomplete="name" style="border-color:rgba(31, 89, 177, 1);"/>
    </div>
    <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
        <label for="textInputDefault" class="w-fit pl-0.5 text-2x1">Giro de empresa</label>
        <input required wire:model="giro_empresa" id="giro_empresa" type="text" class="w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 
        text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed 
        disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark" name="giro_empresa" 
        placeholder="Ingrese Giro de empresa" autocomplete="name" style="border-color:rgba(31, 89, 177, 1);"/>
    </div>
 </div>
 <br>
  <div class="flex w-full max-w-2xl gap-4 text-on-surface dark:text-on-surface-dark">
        <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
        <label for="textInputDefault" class="w-fit pl-0.5 text-2x1">Ubicación</label>
        <input  wire:model="ubicacion" id="ubicacion" type="text" class="w-full rounded-radius border border-outline bg-surface-alt px-2 py-2
         text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed 
         disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark" name="ubicacion" 
         placeholder="Ingrese Ubicación de la empresa" autocomplete="name" style="border-color:rgba(31, 89, 177, 1);"/>
    </div>
    <div x-data class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
        <label for="phoneInput" class="w-fit pl-0.5 text-2x1">Telefono</label>
        <input  wire:model="telefono" id="telefono" type="text" class="w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 text-sm 
        focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 
        dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark" x-mask="(999) 999-9999" 
        name="telefono" autocomplete="tel-national" placeholder="(999) 999-9999" style="border-color:rgba(31, 89, 177, 1);"/>
    </div>
  </div>
  <br>
   <div class="flex w-full max-w-2xl gap-4 text-on-surface dark:text-on-surface-dark">
        <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
            <label for="textInputDefault" class="w-fit pl-0.5 text-2x1">Representante</label>
            <input required wire:model="representante" id="representante" type="text" class="w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 
            text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed 
            disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark" name="representante" 
            placeholder="Ingrese representantes" autocomplete="name" style="border-color:rgba(31, 89, 177, 1);"/>
        </div>
            <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                <label for="fecha_analisis" class="w-fit pl-0.5 text-2x1">Fecha de análisis</label>
                <input required wire:model="fecha_analisis" id="fecha_analisis" type="date" name="fecha_analisis" class="w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 
                text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary 
                disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                style="border-color:rgba(31, 89, 177, 1);" />
            </div>
            
   </div>
     <br>
    <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
        <label for="textInputDefault" class="w-fit pl-0.5 text-2x1">Colaborador</label>
        <input required wire:model="colaborador" id="colaborador" type="text" class="w-full rounded-radius border border-outline bg-surface-alt px-2 py-2
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
                handleDrop(e) {
                    this.isDropping = false;
                    const file = e.dataTransfer.files[0];
                    if (file && file.type.startsWith('image/')) {
                        $wire.upload('img', file);
                    }
                }
            }"
            x-on:drop.prevent="handleDrop($event)"
            x-on:dragover.prevent="isDropping = true"
            x-on:dragleave.prevent="isDropping = false"
        >
            <span class="w-fit pl-0.5 text-2x1">Importa la imagen de la portada</span>

            <div 
                class="flex w-full flex-col items-center justify-center gap-2 rounded-radius border border-dashed p-8 transition"
                :class="isDropping ? 'border-primary bg-primary/5' : 'border-gray-300'" style="border-color:rgba(31, 89, 177, 1);"
            >
                <input 
                    wire:model="img" 
                    id="img" 
                    type="file" 
                    class="sr-only" 
                    accept="image/png,image/jpeg" 
                />

                <label for="img" class="cursor-pointer font-medium text-primary">
                    Arrastra o carga tu imagen aquí
                </label>

                <small>PNG, JPG - Max 5MB</small>

                {{-- Vista previa --}}
                @if ($img)
                    <img src="{{ $img->temporaryUrl() }}" class="w-32 h-32 object-cover mt-2 rounded" />
                @endif
            </div>
        </div>

    
        <div 
            class="flex w-full max-w-xl text-center flex-col gap-1"
            x-data="{
                isDropping: false,
                handleDrop(e) {
                    this.isDropping = false;
                    const file = e.dataTransfer.files[0];
                    if (file && file.type.startsWith('image/')) {
                        $wire.upload('logo', file);
                    }
                }
            }"
            x-on:drop.prevent="handleDrop($event)"
            x-on:dragover.prevent="isDropping = true"
            x-on:dragleave.prevent="isDropping = false"
        >
            <span class="w-fit pl-0.5 text-2x1">Importa el logo de la empresa</span>

            <div 
                class="flex w-full flex-col items-center justify-center gap-2 rounded-radius border border-dashed p-8 transition"
                :class="isDropping ? 'border-primary bg-primary/5' : 'border-gray-300'"
                style="border-color:rgba(31, 89, 177, 1);"
            >
                <input 
                    wire:model="logo" 
                    id="logo" 
                    type="file"
                    class="sr-only" 
                    accept="image/png,image/jpeg" 
                />

                <label for="logo" class="cursor-pointer font-medium text-primary">
                    Arrastra o carga tu imagen aquí
                </label>

                <small>PNG, JPG - Max 5MB</small>

                {{-- Vista previa --}}
                @if ($logo)
                    <img src="{{ $logo->temporaryUrl() }}" class="w-32 h-32 object-cover mt-2 rounded" />
                @endif
            </div>
        </div>
    </div>
  <br>

  <button type="submit" class="inline-flex justify-center items-center gap-2 whitespace-nowrap rounded-radius bg-success border border-success dark:border-success px-4 py-2 text-sm font-medium tracking-wide text-on-success transition hover:opacity-75 text-center focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-success active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-success dark:text-on-success dark:focus-visible:outline-success">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="size-5 fill-on-success dark:fill-on-success" fill="currentColor">
                    <path fill-rule="evenodd" d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z" clip-rule="evenodd" />
                </svg>
                Crear empresa
            </button>
  </form>
  <br>

    Esquema de Informe
    @foreach ($titles as $title)
        <div class="title-wrapper">
            <label>
                <input
                    value="{{ $title->id }}"
                    id="title_{{ $title->id }}"
                    wire:model="title"
                    type="checkbox"
                    class="toggle-subtitles"
                />
                <strong>{{ $title->nombre }}</strong>
            </label>
            <div class="subtitles" style="display: none; margin-left: 20px;">
                @foreach ($title->subtitles as $subtitle)
                    <div class="subtitle-wrapper">
                        <label>
                            <input
                                value="{{ $subtitle->id }}"
                                id="subtitle_{{ $subtitle->id }}"
                                wire:model="subtitle"
                                type="checkbox"
                                class="toggle-sections"
                            />
                            {{ $subtitle->nombre }}
                        </label>

                        <ul class="sections" style="display: none; margin-left: 20px;">
                            @foreach ($subtitle->sections as $section)
                                <li>
                                    <label>
                                        <input
                                            value="{{ $section->id }}"
                                            id="section_{{ $section->id }}"
                                            wire:model="section"
                                            type="checkbox"
                                            class="toggle-subtitles"
                                        />
                                        {{ $section->nombre }}
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <!-- JavaScript -->
    <script>
        document.addEventListener('change', function (event) {
            // Mostrar/Ocultar subtítulos
            if (event.target.matches('.toggle-subtitles')) {
                const subtitlesDiv = event.target.closest('.title-wrapper').querySelector('.subtitles');
                subtitlesDiv.style.display = event.target.checked ? 'block' : 'none';
            }

            // Mostrar/Ocultar secciones
            if (event.target.matches('.toggle-sections')) {
                const sectionsUl = event.target.closest('.subtitle-wrapper').querySelector('.sections');
                sectionsUl.style.display = event.target.checked ? 'block' : 'none';
            }
        });
    </script>
</div>
