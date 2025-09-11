<form  wire:submit='store' class="space-y-4 w-full p-6 bg-surface-alt dark:bg-surface-dark-alt rounded-lg shadow-md">
<div>
    Creación de reportes

 <div class="flex w-full max-w-2xl gap-4 text-on-surface dark:text-on-surface-dark">
    <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
        <label for="textInputDefault" class="w-fit pl-0.5 text-sm">Nombre de empresa</label>
        <input wire:model="nombre_empresa" id="nombre_empresa" type="text" class="w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 
        text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed 
        disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark" name="nombre_empresa" 
        placeholder="Ingrese Nombre de empresa" autocomplete="name"/>
    </div>
    <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
        <label for="textInputDefault" class="w-fit pl-0.5 text-sm">Giro de empresa</label>
        <input wire:model="giro_empresa" id="giro_empresa" type="text" class="w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 
        text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed 
        disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark" name="giro_empresa" 
        placeholder="Ingrese Giro de empresa" autocomplete="name"/>
    </div>
 </div>
 <br>
  <div class="flex w-full max-w-2xl gap-4 text-on-surface dark:text-on-surface-dark">
        <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
        <label for="textInputDefault" class="w-fit pl-0.5 text-sm">Ubicación</label>
        <input wire:model="ubicacion" id="ubicacion" type="text" class="w-full rounded-radius border border-outline bg-surface-alt px-2 py-2
         text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed 
         disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark" name="ubicacion" 
         placeholder="Ingrese Ubicación de la empresa" autocomplete="name"/>
    </div>
    <div x-data class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
        <label for="phoneInput" class="w-fit pl-0.5 text-sm">Telefono</label>
        <input wire:model="telefono" id="telefono" type="text" class="w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 text-sm 
        focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 
        dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark" x-mask="(999) 999-9999" 
        name="telefono" autocomplete="tel-national" placeholder="(999) 999-9999"/>
    </div>
  </div>
  <br>
   <div class="flex w-full max-w-2xl gap-4 text-on-surface dark:text-on-surface-dark">
        <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
            <label for="textInputDefault" class="w-fit pl-0.5 text-sm">Representante</label>
            <input wire:model="representante" id="representante" type="text" class="w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 
            text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed 
            disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark" name="representante" 
            placeholder="Ingrese representantes" autocomplete="name"/>
        </div>
            <div class="flex flex-col w-full">
                <label for="fecha_analisis" class="w-fit pl-0.5 text-sm">Fecha de analisis</label>
                <input wire:model="fecha_analisis" type="date" id="fecha_analisis" name="fecha_analisis">
            </div>
            
   </div>
     <br>
    <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
        <label for="textInputDefault" class="w-fit pl-0.5 text-sm">Colaborador</label>
        <input wire:model="colaborador" id="colaborador" type="text" class="w-full rounded-radius border border-outline bg-surface-alt px-2 py-2
         text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed 
         disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark" name="colaborador" 
         placeholder="Ingrese Ubicación de la empresa" autocomplete="name"/>
   </div>
     <br>
    <div class="flex w-full max-w-xl text-center flex-col gap-1">
        <span class="w-fit pl-0.5 text-sm text-on-surface dark:text-on-surface-dark">Importa el logo de la empresa</span>
        <div class="flex w-full flex-col items-center justify-center gap-2 rounded-radius border border-dashed border-outline p-8 text-on-surface dark:border-outline-dark dark:text-on-surface-dark">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" fill="currentColor" class="w-12 h-12 opacity-75">
                <path fill-rule="evenodd" d="M10.5 3.75a6 6 0 0 0-5.98 6.496A5.25 5.25 0 0 0 6.75 20.25H18a4.5 4.5 0 0 0 2.206-8.423 3.75 3.75 0 0 0-4.133-4.303A6.001 6.001 0 0 0 10.5 3.75Zm2.03 5.47a.75.75 0 0 0-1.06 0l-3 3a.75.75 0 1 0 1.06 1.06l1.72-1.72v4.94a.75.75 0 0 0 1.5 0v-4.94l1.72 1.72a.75.75 0 1 0 1.06-1.06l-3-3Z" clip-rule="evenodd"/>
            </svg>
            <div class="group">
                <label for="fileInputDragDrop" class="font-medium text-primary group-focus-within:underline dark:text-primary-dark">
                    <input wire:model="logo" id="logo" type="file" class="sr-only" aria-describedby="validFileFormats" />
                    Carga
                </label>
                tu imagen aca
            </div>
            <small id="validFileFormats">PNG, JPG - Max 5MB</small>
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
