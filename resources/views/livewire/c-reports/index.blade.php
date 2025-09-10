<div>
    Creación de reportes

    <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
        <label for="textInputDefault" class="w-fit pl-0.5 text-sm">Nombre de empresa</label>
        <input id="textInputDefault" type="text" class="w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark" name="name" placeholder="Enter your name" autocomplete="name"/>
    </div>
    <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
        <label for="textInputDefault" class="w-fit pl-0.5 text-sm">Giro de empresa</label>
        <input id="textInputDefault" type="text" class="w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark" name="name" placeholder="Enter your name" autocomplete="name"/>
    </div>
        <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
        <label for="textInputDefault" class="w-fit pl-0.5 text-sm">Ubicación</label>
        <input id="textInputDefault" type="text" class="w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark" name="name" placeholder="Enter your name" autocomplete="name"/>
    </div>
    <div x-data class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
        <label for="phoneInput" class="w-fit pl-0.5 text-sm">Telefono</label>
        <input id="phoneInput" type="text" class="w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark" x-mask="(999) 999-9999" name="phone" autocomplete="tel-national" placeholder="(999) 999-9999"/>
    </div>
    <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
        <label for="textInputDefault" class="w-fit pl-0.5 text-sm">Representante</label>
        <input id="textInputDefault" type="text" class="w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark" name="name" placeholder="Enter your name" autocomplete="name"/>
    </div>
    <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
        <label for="textInputDefault" class="w-fit pl-0.5 text-sm">Fecha de analisis</label>
        <input id="textInputDefault" type="text" class="w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark" name="name" placeholder="Enter your name" autocomplete="name"/>
    </div>
<div>
    <h2 class="text-lg font-bold mb-4">Agregar Colaboradores</h2>


        <div class="flex items-center gap-2 mb-2">
            <input type="text"
                   wire:model="colaboradores.{{ $index }}"
                   placeholder="Nombre del colaborador"
                   class="border rounded px-2 py-1 w-full">

            <!-- Botón para eliminar -->


    <!-- Botón para agregar más -->
    <button type="button"
            wire:click="addColaborador"
            class="bg-green-500 text-white px-2 py-1 rounded">
        +
    </button>
</div>





    <div class="flex w-full max-w-xl text-center flex-col gap-1">
        <span class="w-fit pl-0.5 text-sm text-on-surface dark:text-on-surface-dark">Importa el logo de la empresa</span>
        <div class="flex w-full flex-col items-center justify-center gap-2 rounded-radius border border-dashed border-outline p-8 text-on-surface dark:border-outline-dark dark:text-on-surface-dark">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" fill="currentColor" class="w-12 h-12 opacity-75">
                <path fill-rule="evenodd" d="M10.5 3.75a6 6 0 0 0-5.98 6.496A5.25 5.25 0 0 0 6.75 20.25H18a4.5 4.5 0 0 0 2.206-8.423 3.75 3.75 0 0 0-4.133-4.303A6.001 6.001 0 0 0 10.5 3.75Zm2.03 5.47a.75.75 0 0 0-1.06 0l-3 3a.75.75 0 1 0 1.06 1.06l1.72-1.72v4.94a.75.75 0 0 0 1.5 0v-4.94l1.72 1.72a.75.75 0 1 0 1.06-1.06l-3-3Z" clip-rule="evenodd"/>
            </svg>
            <div class="group">
                <label for="fileInputDragDrop" class="font-medium text-primary group-focus-within:underline dark:text-primary-dark">
                    <input id="fileInputDragDrop" type="file" class="sr-only" aria-describedby="validFileFormats" />
                    Carga
                </label>
                tu imagen aca
            </div>
            <small id="validFileFormats">PNG, JPG - Max 5MB</small>
        </div>
    </div>

    Esquema de Informe
    @foreach ($titles as $title)
        <div class="title-wrapper">
            <label>
                <input type="checkbox" class="toggle-subtitles">
                <strong>{{ $title->nombre }}</strong>
            </label>

            <div class="subtitles" style="display: none; margin-left: 20px;">
                @foreach ($title->subtitles as $subtitle)
                    <div class="subtitle-wrapper">
                        <label>
                            <input type="checkbox" class="toggle-sections">
                            {{ $subtitle->nombre }}
                        </label>

                        <ul class="sections" style="display: none; margin-left: 20px;">
                            @foreach ($subtitle->sections as $section)
                                <li>
                                    <label>
                                        <input type="checkbox" name="sections[]">
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
