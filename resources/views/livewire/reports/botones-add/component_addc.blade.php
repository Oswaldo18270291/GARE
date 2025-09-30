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

    <!-- Grupo Imagen 1 + Leyenda -->
    <div class="flex flex-col items-center w-full max-w-xs">
    <div 
        class="flex w-full flex-col gap-1"
        x-data="{
            isDropping: false,
            errorMsg: '',
            handleFile(file, field, input) {
                this.errorMsg = '';
                if (file && file.type.startsWith('image/')) {
                    $wire.upload(field, file);
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
        x-on:drop.prevent="handleDrop($event, 'img1', $refs.img1Input)"
        x-on:dragover.prevent="isDropping = true"
        x-on:dragleave.prevent="isDropping = false">

        <span class="w-fit pl-0.5 text-2x1">Importa la imagen 1</span>

        <div class="bg-white flex w-full flex-col items-center justify-center gap-2 rounded-radius border border-dashed p-8 transition"
             :class="isDropping ? 'border-primary bg-primary/5' : 'border-gray-300'"
             style="border-color:rgba(31, 89, 177, 1);">

            <input id="img1" type="file" class="sr-only" accept="image/png,image/jpeg"
                   x-ref="img1Input"
                   x-on:change="handleFile($event.target.files[0], 'img1', $event.target)" />
            @error('img1') 
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
            <label for="img1" class="cursor-pointer font-medium text-primary">
                Arrastra o carga tu imagen aquí
            </label>
            <small>PNG, JPG - Max 5MB</small>

            @if ($img1)
                <img src="{{ $img1->temporaryUrl() }}" class="w-32 h-32 object-cover mt-2 rounded" />
            @endif

            <p x-show="errorMsg" x-text="errorMsg" class="text-red-600 text-sm mt-2"></p>
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


    <!-- Grupo Imagen 2 + Leyenda -->
  <div class="flex flex-col items-center w-full max-w-xs">
    <div 
        class="flex w-full flex-col gap-1"
        x-data="{
            isDropping: false,
            errorMsg: '',
            handleFile(file, field, input) {
                this.errorMsg = '';
                if (file && file.type.startsWith('image/')) {
                    $wire.upload(field, file);
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
        x-on:drop.prevent="handleDrop($event, 'img2', $refs.img2Input)"
        x-on:dragover.prevent="isDropping = true"
        x-on:dragleave.prevent="isDropping = false">

        <span class="w-fit pl-0.5 text-2x1">Importa la imagen 2</span>

        <div class="bg-white flex w-full flex-col items-center justify-center gap-2 rounded-radius border border-dashed p-8 transition"
             :class="isDropping ? 'border-primary bg-primary/5' : 'border-gray-300'"
             style="border-color:rgba(31, 89, 177, 1);">

            <input id="img2" type="file" class="sr-only" accept="image/png,image/jpeg"
                   x-ref="img2Input"
                   x-on:change="handleFile($event.target.files[0], 'img2', $event.target)" />
            @error('img2') 
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
            <label for="img2" class="cursor-pointer font-medium text-primary">
                Arrastra o carga tu imagen aquí
            </label>
            <small>PNG, JPG - Max 5MB</small>

            @if ($img2)
                <img src="{{ $img2->temporaryUrl() }}" class="w-32 h-32 object-cover mt-2 rounded" />
            @endif

            <p x-show="errorMsg" x-text="errorMsg" class="text-red-600 text-sm mt-2"></p>
        </div>
    </div>

    <div class="flex w-full flex-col gap-1 mt-2">
        <label class="w-fit pl-0.5 text-2x1">Leyenda 2</label>
        <input wire:model="leyenda2" id="leyenda2" type="text"
               class="bg-white w-full rounded-radius border border-outline px-2 py-2 text-sm"
               placeholder="Ingrese texto de leyenda 2"
               style="border-color:rgba(31, 89, 177, 1);" />
            @error('leyenda2') 
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
    </div>
</div>


    <!-- Grupo Imagen 3 + Leyenda -->
  <div class="flex flex-col items-center w-full max-w-xs">
    <div 
        class="flex w-full flex-col gap-1"
        x-data="{
            isDropping: false,
            errorMsg: '',
            handleFile(file, field, input) {
                this.errorMsg = '';
                if (file && file.type.startsWith('image/')) {
                    $wire.upload(field, file);
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
        x-on:drop.prevent="handleDrop($event, 'img3', $refs.img3Input)"
        x-on:dragover.prevent="isDropping = true"
        x-on:dragleave.prevent="isDropping = false">

        <span class="w-fit pl-0.5 text-2x1">Importa la imagen 3</span>

        <div class="bg-white flex w-full flex-col items-center justify-center gap-2 rounded-radius border border-dashed p-8 transition"
             :class="isDropping ? 'border-primary bg-primary/5' : 'border-gray-300'"
             style="border-color:rgba(31, 89, 177, 1);">

            <input id="img3" type="file" class="sr-only" accept="image/png,image/jpeg"
                   x-ref="img3Input"
                   x-on:change="handleFile($event.target.files[0], 'img3', $event.target)" />
            @error('img3') 
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
            <label for="img3" class="cursor-pointer font-medium text-primary">
                Arrastra o carga tu imagen aquí
            </label>
            <small>PNG, JPG - Max 5MB</small>

            @if ($img3)
                <img src="{{ $img3->temporaryUrl() }}" class="w-32 h-32 object-cover mt-2 rounded" />
            @endif

            <p x-show="errorMsg" x-text="errorMsg" class="text-red-600 text-sm mt-2"></p>
        </div>
    </div>

    <div class="flex w-full flex-col gap-1 mt-2">
        <label class="w-fit pl-0.5 text-2x1">Leyenda 3</label>
        <input wire:model="leyenda3" id="leyenda3" type="text"
               class="bg-white w-full rounded-radius border border-outline px-2 py-2 text-sm"
               placeholder="Ingrese texto de leyenda 3"
               style="border-color:rgba(31, 89, 177, 1);" />
            @error('leyenda3') 
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror               
    </div>
</div>
</div>
<br>
@if ($titulo=='Mosler: Informe')
4.1.1.	Mosler: Informe.
<table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 10pt; text-align: center;">
  <tr style="background-color: #0f4a75ff; color: white; font-weight: bold;">
    <td style="border: 1px solid black; padding: 5px;">No.</td>
    <td style="border: 1px solid black; padding: 5px;">Riesgo</td>
    <td style="border: 1px solid black; padding: 5px;">F</td>
    <td style="border: 1px solid black; padding: 5px;">S</td>
    <td style="border: 1px solid black; padding: 5px;">P</td>
    <td style="border: 1px solid black; padding: 5px;">E</td>
    <td style="border: 1px solid black; padding: 5px;">PB</td>
    <td style="border: 1px solid black; padding: 5px;">If</td>
    <td style="border: 1px solid black; padding: 5px;">Clase del Riesgo</td>
    <td style="border: 1px solid black; padding: 5px;">Factor de ocurrencia del riesgo</td>
  </tr>

  <tr>
    <td style="border: 1px solid black;">R01</td>
    <td style="border: 1px solid black; text-align: left;">Intrusión.</td>
    <td style="border: 1px solid black;">
    <div class="relative flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="absolute pointer-events-none right-4 top-8 size-5">
            <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
        </svg>
        <select id="os" name="os" class="w-full appearance-none rounded-radius border border-outline bg-surface-alt px-4 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark">
            <option selected>Selecciona</option>
            <option value="mac">1</option>
            <option value="windows">2</option>
            <option value="linux">3</option>
            <option value="windows">4</option>
            <option value="linux">5</option>
        </select>
    </div>
    </td><td style="border: 1px solid black;">
    <div x-data="{ currentVal: 1, minVal: 0, maxVal: 10, decimalPoints: 0, incrementAmount: 1 }" class="flex flex-col gap-1">
        <div x-on:dblclick.prevent class="flex items-center">
            <button x-on:click="currentVal = Math.max(minVal, currentVal - incrementAmount)" class="flex h-10 items-center justify-center rounded-radius border border-outline bg-surface-alt px-4 py-2 text-on-surface hover:opacity-75 focus-visible:z-10 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark dark:focus-visible:outline-primary-dark" aria-label="subtract">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="2" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15"/>
                </svg>
            </button>
            <input x-model="currentVal.toFixed(decimalPoints)" id="counterInput" type="text" class="h-10 w-20 rounded-none bg-transparent text-center text-on-surface-strong focus-visible:z-10 focus-visible:outline-2 focus-visible:outline-primary dark:text-on-surface-dark-strong dark:focus-visible:outline-primary-dark" readonly />
            <button x-on:click="currentVal = Math.min(maxVal, currentVal + incrementAmount)" class="flex h-10 items-center justify-center rounded-radius border border-outline bg-surface-alt px-4 py-2 text-on-surface hover:opacity-75 focus-visible:z-10 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark dark:focus-visible:outline-primary-dark" aria-label="add">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="2" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
            </button>
        </div>
    </div>
    </td><td style="border: 1px solid black;">5</td>
    <td style="border: 1px solid black;">4</td><td style="border: 1px solid black;">5</td><td style="border: 1px solid black;">5</td>
    <td style="border: 1px solid black; background-color: red; color: white; font-weight: bold;">MUY ALTO</td>
    <td style="border: 1px solid black;">90.00</td>
  </tr>

  <tr>
    <td style="border: 1px solid black;">R02</td>
    <td style="border: 1px solid black; text-align: left;">Invasión para ocupación de áreas.</td>
    <td style="border: 1px solid black;">5</td><td style="border: 1px solid black;">5</td><td style="border: 1px solid black;">5</td>
    <td style="border: 1px solid black;">4</td><td style="border: 1px solid black;">5</td><td style="border: 1px solid black;">5</td>
    <td style="border: 1px solid black; background-color: red; color: white; font-weight: bold;">MUY ALTO</td>
    <td style="border: 1px solid black;">90.00</td>
  </tr>

  <tr>
    <td style="border: 1px solid black;">R03</td>
    <td style="border: 1px solid black; text-align: left;">Manifestaciones sociales y movimientos sindicales.</td>
    <td style="border: 1px solid black;">5</td><td style="border: 1px solid black;">5</td><td style="border: 1px solid black;">5</td>
    <td style="border: 1px solid black;">5</td><td style="border: 1px solid black;">5</td><td style="border: 1px solid black;">5</td>
    <td style="border: 1px solid black; background-color: red; color: white; font-weight: bold;">MUY ALTO</td>
    <td style="border: 1px solid black;">90.00</td>
  </tr>

  <tr>
    <td style="border: 1px solid black;">R04</td>
    <td style="border: 1px solid black; text-align: left;">Ciber intrusión con captura y bloqueo de datos de la empresa.</td>
    <td style="border: 1px solid black;">5</td><td style="border: 1px solid black;">5</td><td style="border: 1px solid black;">4</td>
    <td style="border: 1px solid black;">4</td><td style="border: 1px solid black;">5</td><td style="border: 1px solid black;">5</td>
    <td style="border: 1px solid black; background-color: red; color: white; font-weight: bold;">MUY ALTO</td>
    <td style="border: 1px solid black;">82.00</td>
  </tr>

  <tr>
    <td style="border: 1px solid black;">R05</td>
    <td style="border: 1px solid black; text-align: left;">Filtración de información.</td>
    <td style="border: 1px solid black;">5</td><td style="border: 1px solid black;">5</td><td style="border: 1px solid black;">4</td>
    <td style="border: 1px solid black;">4</td><td style="border: 1px solid black;">5</td><td style="border: 1px solid black;">4</td>
    <td style="border: 1px solid black; background-color: yellow; color: black; font-weight: bold;">ALTO</td>
    <td style="border: 1px solid black;">72.00</td>
  </tr>

  <tr>
    <td style="border: 1px solid black;">R06</td>
    <td style="border: 1px solid black; text-align: left;">Emergencias médicas.</td>
    <td style="border: 1px solid black;">4</td><td style="border: 1px solid black;">5</td><td style="border: 1px solid black;">4</td>
    <td style="border: 1px solid black;">4</td><td style="border: 1px solid black;">5</td><td style="border: 1px solid black;">4</td>
    <td style="border: 1px solid black; background-color: yellow; color: black; font-weight: bold;">ALTO</td>
    <td style="border: 1px solid black;">64.00</td>
  </tr>

  <tr>
    <td style="border: 1px solid black;">R07</td>
    <td style="border: 1px solid black; text-align: left;">Tempestad y/o lluvia con inundaciones de mediana intensidad.</td>
    <td style="border: 1px solid black;">5</td><td style="border: 1px solid black;">5</td><td style="border: 1px solid black;">3</td>
    <td style="border: 1px solid black;">4</td><td style="border: 1px solid black;">4</td><td style="border: 1px solid black;">4</td>
    <td style="border: 1px solid black; background-color: #00B050; color: white; font-weight: bold;">NORMAL</td>
    <td style="border: 1px solid black;">47.36</td>
  </tr>

  <tr>
    <td style="border: 1px solid black;">R08</td>
    <td style="border: 1px solid black; text-align: left;">Lesiones.</td>
    <td style="border: 1px solid black;">5</td><td style="border: 1px solid black;">5</td><td style="border: 1px solid black;">3</td>
    <td style="border: 1px solid black;">4</td><td style="border: 1px solid black;">4</td><td style="border: 1px solid black;">4</td>
    <td style="border: 1px solid black; background-color: #92D050; color: black; font-weight: bold;">BAJO</td>
    <td style="border: 1px solid black;">47.36</td>
  </tr>

  <tr>
    <td style="border: 1px solid black;">R09</td>
    <td style="border: 1px solid black; text-align: left;">Amenazas a empleados.</td>
    <td style="border: 1px solid black;">5</td><td style="border: 1px solid black;">5</td><td style="border: 1px solid black;">3</td>
    <td style="border: 1px solid black;">3</td><td style="border: 1px solid black;">4</td><td style="border: 1px solid black;">3</td>
    <td style="border: 1px solid black; background-color: #92D050; color: black; font-weight: bold;">BAJO</td>
    <td style="border: 1px solid black;">38.40</td>
  </tr>

  <tr>
    <td style="border: 1px solid black;">R10</td>
    <td style="border: 1px solid black; text-align: left;">Incendio.</td>
    <td style="border: 1px solid black;">5</td><td style="border: 1px solid black;">5</td><td style="border: 1px solid black;">5</td>
    <td style="border: 1px solid black;">3</td><td style="border: 1px solid black;">3</td><td style="border: 1px solid black;">3</td>
    <td style="border: 1px solid black; background-color: #92D050; color: black; font-weight: bold;">BAJO</td>
    <td style="border: 1px solid black;">28.80</td>
  </tr>
</table>
@endif
@if ($titulo=='Organigrama de Riesgos')
<br>
4.1.2	Organigrama de Riesgos.
<table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 11pt; text-align: left;">
  <!-- Encabezado Cibernéticos -->
  <tr style="background-color: #0f4a75ff; font-weight: bold;">
    <td colspan="2" style="border: 1px solid black; padding: 6px;">Cibernéticos.</td>
  </tr>
  <tr>
    <td style="border: 1px solid black; padding: 6px; width: 40px; text-align: center;">1</td>
    <td style="border: 1px solid black; padding: 6px;">R04 - Ciber intrusión con captura y bloqueo de datos de la empresa.</td>
  </tr>

  <!-- Encabezado Naturales -->
  <tr style="background-color: #0f4a75ff; font-weight: bold;">
    <td colspan="2" style="border: 1px solid black; padding: 6px;">Naturales</td>
  </tr>
  <tr>
    <td style="border: 1px solid black; padding: 6px; text-align: center;">1</td>
    <td style="border: 1px solid black; padding: 6px;">R07 - Tempestad y/o lluvia con inundaciones de mediana intensidad.</td>
  </tr>

  <!-- Encabezado Sociales -->
  <tr style="background-color: #00B0F0; font-weight: bold;">
    <td colspan="2" style="border: 1px solid black; padding: 6px;">Sociales (Personas)</td>
  </tr>
  <tr>
    <td style="border: 1px solid black; padding: 6px; text-align: center;">1</td>
    <td style="border: 1px solid black; padding: 6px;">R03 - Manifestaciones sociales y movimientos sindicales.</td>
  </tr>
  <tr>
    <td style="border: 1px solid black; padding: 6px; text-align: center;">2</td>
    <td style="border: 1px solid black; padding: 6px;">R02 - Invasión para ocupación de áreas.</td>
  </tr>
  <tr>
    <td style="border: 1px solid black; padding: 6px; text-align: center;">3</td>
    <td style="border: 1px solid black; padding: 6px;">R01 - Intrusión.</td>
  </tr>
  <tr>
    <td style="border: 1px solid black; padding: 6px; text-align: center;">4</td>
    <td style="border: 1px solid black; padding: 6px;">R05 - Filtración de información.</td>
  </tr>
  <tr>
    <td style="border: 1px solid black; padding: 6px; text-align: center;">5</td>
    <td style="border: 1px solid black; padding: 6px;">R08 - Lesiones.</td>
  </tr>
  <tr>
    <td style="border: 1px solid black; padding: 6px; text-align: center;">6</td>
    <td style="border: 1px solid black; padding: 6px;">R06 - Emergencias médicas.</td>
  </tr>
  <tr>
    <td style="border: 1px solid black; padding: 6px; text-align: center;">7</td>
    <td style="border: 1px solid black; padding: 6px;">R09 - Amenazas a empleados.</td>
  </tr>
  <tr>
    <td style="border: 1px solid black; padding: 6px; text-align: center;">8</td>
    <td style="border: 1px solid black; padding: 6px;">R10 - Incendio.</td>
  </tr>
</table>
<br>
@endif
@if ($titulo=='Nivel de Riesgo-Gráfico de Consecuencia x Factor de Ocurrencia')
GRAFICA
4.1.3	Nivel de Riesgo-Gráfico de Consecuencia x Factor de Ocurrencia
  <canvas id="riesgosChart" width="800" height="400"></canvas>

  <script>
    const ctx = document.getElementById('riesgosChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: [
          "R01 INTRUSIÓN",
          "R02 INVASIÓN PARA...",
          "R03 MANIFESTACIONES",
          "R04 CIBER INTRUSIÓN",
          "R05 FILTRACIÓN INFORMACIÓN",
          "R06 EMERGENCIAS MÉDICAS",
          "R07 LLUVIAS/INUNDACIONES",
          "R08 LESIONES",
          "R09 AMENAZAS A EMPLEADOS",
          "R10 INCENDIO"
        ],
        datasets: [{
          label: 'Factor de ocurrencia',
          data: [90, 90, 90, 82, 72, 64, 47.36, 47.36, 38.40, 28.80],
          backgroundColor: [
            "red","red","red","red", // Muy alto
            "orange","orange",       // Alto
            "green","green",            // Normal
            "lightblue","lightblue"  // Bajo
          ]
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: false
          },
          title: {
            display: true,
            text: 'Factor de ocurrencia'
          }
        },
        scales: {
          x: {
            ticks: {
              maxRotation: 90,
              minRotation: 60
            }
          },
          y: {
            beginAtZero: true,
            max: 100
          }
        }
      }
    });
  </script>
<br>

Características del Riesgo.
<table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 10pt; text-align: left;">
  <tr style="font-weight: bold; text-align: center;">
    <td style="border: 1px solid black; padding: 6px; background-color: #00B050; color: black;">
      Rango Normal (Zona de Seguridad)
    </td>
    <td style="border: 1px solid black; padding: 6px; background-color: #FFFF00; color: black;">
      Rango Intermedio (Zona de atención)
    </td>
    <td style="border: 1px solid black; padding: 6px; background-color: #FF0000; color: white;">
      Rango de atención inmediata (Zona intolerable)
    </td>
  </tr>

  <!-- Fila de riesgos -->
  <tr style="vertical-align: top;">
    <td style="border: 1px solid black; padding: 6px; height: 200px;"></td>
    <td style="border: 1px solid black; padding: 6px; height: 200px;"></td>
    <td style="border: 1px solid black; padding: 6px; height: 200px;">
      ✓ R.02 Invasión para ocupación de áreas.<br>
      ✓ R.03 Manifestaciones sociales y movimientos sindicales.<br>
      ✓ R.05 Filtración de información.<br>
      ✓ R.04 Ciberintrusión con captura y bloqueo de datos de la empresa.<br>
      ✓ R.07 Tempestad y/o lluvia con inundaciones de mediana intensidad.<br>
      ✓ R.08 Lesiones.<br>
      ✓ R.01 Intrusión.<br>
      ✓ R.06 Emergencias médicas.<br>
      ✓ R.09 Amenazas a empleados.<br>
      ✓ R.10 Incendio.
    </td>
  </tr>

  <!-- Fila de descripciones -->
  <tr style="vertical-align: top;">
    <td style="border: 1px solid black; padding: 6px; background-color: #00B050; color: black;">
      Este rango representa riesgos de baja probabilidad y bajo impacto. Los eventos situados en este rango normalmente se consideran aceptables y dentro de los límites normales de operación. 
      Las consecuencias, si ocurren, serían bajas y fácilmente controladas por la organización. 
      Normalmente, no se necesita ninguna acción correctiva inmediata, pero se deben mantener los controles actuales y monitorear continuamente los riesgos para garantizar que permanezcan dentro de esta zona de seguridad.
    </td>
    <td style="border: 1px solid black; padding: 6px; background-color: #FFFF00; color: black;">
      En este rango, los riesgos presentan una probabilidad y/o impactos moderados. 
      Los eventos en el área intermedia requieren atención, ya que pueden causar perturbaciones significativas en la operación, aunque no de manera catastrófica. 
      Se recomiendan medidas preventivas o correctivas para mitigar el impacto o la probabilidad de ocurrencia, con un monitoreo constante para evitar que migren al área de riesgo intolerable.
    </td>
    <td style="border: 1px solid black; padding: 6px; background-color: #FF0000; color: white;">
      Este rango representa riesgos de alta probabilidad y/o alto impacto, siendo considerados inaceptables y requieren intervención inmediata. 
      Cualquier evento en este rango puede causar graves consecuencias para la organización, comprometiendo seriamente sus objetivos y/o procesos. 
      La mitigación de estos riesgos debe ser la máxima prioridad, y se requieren acciones inmediatas para reducir el impacto y/o la probabilidad de ocurrencia.
    </td>
  </tr>
</table>
<br>
@endif
@if ($titulo=='Control Existente contra Control Ideal')
5.1	Control Existente contra Control Ideal.
<table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 10pt; text-align: center;">
  <tr style="background-color: #0070C0; color: white; font-weight: bold;">
    <td style="border: 1px solid black; padding: 6px;">Orden.</td>
    <td style="border: 1px solid black; padding: 6px;">Control</td>
    <td style="border: 1px solid black; padding: 6px;">Existente</td>
    <td style="border: 1px solid black; padding: 6px;">Ideal</td>
  </tr>

  <tr>
    <td style="border: 1px solid black;">C01</td>
    <td style="border: 1px solid black; text-align: left;">Brigada de primeros auxilios</td>
    <td style="border: 1px solid black; background-color: yellow; font-weight: bold;">1</td>
    <td style="border: 1px solid black;">5</td>
  </tr>

  <tr>
    <td style="border: 1px solid black;">C02</td>
    <td style="border: 1px solid black; text-align: left;">Comité integrado de gestión de riesgos corporativos</td>
    <td style="border: 1px solid black; background-color: yellow; font-weight: bold;">3</td>
    <td style="border: 1px solid black;">5</td>
  </tr>

  <tr>
    <td style="border: 1px solid black;">C03</td>
    <td style="border: 1px solid black; text-align: left;">Consultoría de seguridad (externa)</td>
    <td style="border: 1px solid black; background-color: yellow; font-weight: bold;">1</td>
    <td style="border: 1px solid black;">5</td>
  </tr>

  <tr>
    <td style="border: 1px solid black;">C04</td>
    <td style="border: 1px solid black; text-align: left;">Consultoría de seguridad (interna)</td>
    <td style="border: 1px solid black; background-color: yellow; font-weight: bold;">2</td>
    <td style="border: 1px solid black;">5</td>
  </tr>

  <tr>
    <td style="border: 1px solid black;">C05</td>
    <td style="border: 1px solid black; text-align: left;">Controlador de acceso</td>
    <td style="border: 1px solid black; background-color: yellow; font-weight: bold;">1</td>
    <td style="border: 1px solid black;">5</td>
  </tr>

  <tr>
    <td style="border: 1px solid black;">C06</td>
    <td style="border: 1px solid black; text-align: left;">Cumplimentar leyes federales, provinciales/estaduales y/o municipales</td>
    <td style="border: 1px solid black; background-color: yellow; font-weight: bold;">2</td>
    <td style="border: 1px solid black;">5</td>
  </tr>

  <tr>
    <td style="border: 1px solid black;">C07</td>
    <td style="border: 1px solid black; text-align: left;">Cumplimentar regulaciones de organizaciones reguladoras</td>
    <td style="border: 1px solid black; background-color: yellow; font-weight: bold;">2</td>
    <td style="border: 1px solid black;">5</td>
  </tr>

  <tr>
    <td style="border: 1px solid black;">C08</td>
    <td style="border: 1px solid black; text-align: left;">Cumplimentar regulaciones de organizaciones reguladoras</td>
    <td style="border: 1px solid black; background-color: yellow; font-weight: bold;">2</td>
    <td style="border: 1px solid black;">5</td>
  </tr>

  <tr>
    <td style="border: 1px solid black;">C09</td>
    <td style="border: 1px solid black; text-align: left;">Equipo de seguridad</td>
    <td style="border: 1px solid black; background-color: yellow; font-weight: bold;">3</td>
    <td style="border: 1px solid black;">5</td>
  </tr>

  <tr>
    <td style="border: 1px solid black;">C10</td>
    <td style="border: 1px solid black; text-align: left;">Sistemas y procesos contra ciber ataques</td>
    <td style="border: 1px solid black; background-color: yellow; font-weight: bold;">2</td>
    <td style="border: 1px solid black;">5</td>
  </tr>
</table>

<br>
@endif
@if ($titulo=='Organigrama de Controles')
<h1>5.2	Organigrama de Controles.</h1>
<table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 11pt; text-align: left;">
  <!-- Encabezado Humanos -->
  <tr style="background-color: #0070C0; color: white; font-weight: bold;">
    <td colspan="3" style="border: 1px solid black; padding: 6px;">Humanos</td>
  </tr>
  <tr>
    <td style="border: 1px solid black; padding: 6px;">1</td>
    <td style="border: 1px solid black; padding: 6px;"><b>C04</b></td>
    <td style="border: 1px solid black; padding: 6px;">Consultoría de seguridad (interna)</td>
  </tr>
  <tr>
    <td style="border: 1px solid black; padding: 6px;">2</td>
    <td style="border: 1px solid black; padding: 6px;"><b>C05</b></td>
    <td style="border: 1px solid black; padding: 6px;">Controlador de acceso</td>
  </tr>
  <tr>
    <td style="border: 1px solid black; padding: 6px;">3</td>
    <td style="border: 1px solid black; padding: 6px;"><b>C09</b></td>
    <td style="border: 1px solid black; padding: 6px;">Equipo de seguridad</td>
  </tr>
  <tr>
    <td style="border: 1px solid black; padding: 6px;">4</td>
    <td style="border: 1px solid black; padding: 6px;"><b>C01</b></td>
    <td style="border: 1px solid black; padding: 6px;">Brigada de primeros auxilios</td>
  </tr>
  <tr>
    <td style="border: 1px solid black; padding: 6px;">5</td>
    <td style="border: 1px solid black; padding: 6px;"><b>C03</b></td>
    <td style="border: 1px solid black; padding: 6px;">Consultoría de seguridad</td>
  </tr>

  <!-- Encabezado Políticos -->
  <tr style="background-color: #0070C0; color: white; font-weight: bold; font-style: italic;">
    <td colspan="3" style="border: 1px solid black; padding: 6px;">Políticos y regulatorios.</td>
  </tr>
  <tr>
    <td style="border: 1px solid black; padding: 6px;">1</td>
    <td style="border: 1px solid black; padding: 6px;"><b>C06</b></td>
    <td style="border: 1px solid black; padding: 6px;">Cumplimentar leyes federales, provinciales/estaduales y/o municipales</td>
  </tr>
  <tr>
    <td style="border: 1px solid black; padding: 6px;">2</td>
    <td style="border: 1px solid black; padding: 6px;"><b>C08</b></td>
    <td style="border: 1px solid black; padding: 6px;">Cumplimentar regulaciones de organizaciones reguladoras</td>
  </tr>
  <tr>
    <td style="border: 1px solid black; padding: 6px;">3</td>
    <td style="border: 1px solid black; padding: 6px;"><b>C07</b></td>
    <td style="border: 1px solid black; padding: 6px;">Cumplimentar regulaciones de organizaciones reguladoras</td>
  </tr>

  <!-- Encabezado Procesos -->
  <tr style="background-color: #0070C0; color: white; font-weight: bold; font-style: italic;">
    <td colspan="3" style="border: 1px solid black; padding: 6px;">Procesos (Organizacionales)</td>
  </tr>
  <tr>
    <td style="border: 1px solid black; padding: 6px;">1</td>
    <td style="border: 1px solid black; padding: 6px;"><b>C02</b></td>
    <td style="border: 1px solid black; padding: 6px;">Comité integrado de gestión de riesgos corporativos</td>
  </tr>

  <!-- Encabezado Técnicos -->
  <tr style="background-color: #0070C0; color: white; font-weight: bold; font-style: italic;">
    <td colspan="3" style="border: 1px solid black; padding: 6px;">Técnicos (Físicos y Electrónicos).</td>
  </tr>
  <tr>
    <td style="border: 1px solid black; padding: 6px;">1</td>
    <td style="border: 1px solid black; padding: 6px;"><b>C10</b></td>
    <td style="border: 1px solid black; padding: 6px;">Sistemas y procesos contra ciber ataques</td>
  </tr>
</table>
<br>
@endif
@if ($titulo=='Control: Consultoría de seguridad interna')
5.4	 Control: Consultoría de seguridad interna. - 5.13 
<table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 12pt; text-align: left;">
  <tr>
    <td style="border: 1px solid black; padding: 8px;"><b>¿Qué?</b></td>
    <td style="border: 1px solid black; padding: 8px;">
        <textarea id="textArea" class="w-full rounded-radius border border-outline bg-surface-alt 
        px-2.5 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary 
        disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 
        dark:focus-visible:outline-primary-dark" rows="3" placeholder="Coloca la informacion"></textarea>
    </td>
  </tr>
  <tr>
    <td style="border: 1px solid black; padding: 8px;"><b>¿Cómo?</b></td>
    <td style="border: 1px solid black; padding: 8px;">
        <textarea id="textArea" class="w-full rounded-radius border border-outline bg-surface-alt 
        px-2.5 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary 
        disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 
        dark:focus-visible:outline-primary-dark" rows="3" placeholder="Coloca la informacion"></textarea>
    </td>
  </tr>
  <tr>
    <td style="border: 1px solid black; padding: 8px;"><b>¿Quién?</b></td>
    <td style="border: 1px solid black; padding: 8px;">
        <textarea id="textArea" class="w-full rounded-radius border border-outline bg-surface-alt 
        px-2.5 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary 
        disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 
        dark:focus-visible:outline-primary-dark" rows="3" placeholder="Coloca la informacion"></textarea>    
    </td>
  </tr>
  <tr>
    <td style="border: 1px solid black; padding: 8px;"><b>¿Por qué?</b></td>
    <td style="border: 1px solid black; padding: 8px;">
        <textarea id="textArea" class="w-full rounded-radius border border-outline bg-surface-alt 
        px-2.5 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary 
        disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 
        dark:focus-visible:outline-primary-dark" rows="3" placeholder="Coloca la informacion"></textarea>    
    </td>
  </tr>
  <tr>
    <td style="border: 1px solid black; padding: 8px;"><b>¿Dónde?</b></td>
    <td style="border: 1px solid black; padding: 8px;">
        <textarea id="textArea" class="w-full rounded-radius border border-outline bg-surface-alt 
        px-2.5 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary 
        disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 
        dark:focus-visible:outline-primary-dark" rows="3" placeholder="Coloca la informacion"></textarea>
    </td>
  </tr>
  <tr>
    <td style="border: 1px solid black; padding: 8px;"><b>¿Cuánto?</b></td>
    <td style="border: 1px solid black; padding: 8px;">
        <textarea id="textArea" class="w-full rounded-radius border border-outline bg-surface-alt 
        px-2.5 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary 
        disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 
        dark:focus-visible:outline-primary-dark" rows="3" placeholder="Coloca la informacion"></textarea>
    </td>
  </tr>
  <tr>
    <td style="border: 1px solid black; padding: 8px;"><b>¿Cuándo?</b></td>
    <td style="border: 1px solid black; padding: 8px;">
        <textarea id="textArea" class="w-full rounded-radius border border-outline bg-surface-alt 
        px-2.5 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary 
        disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 
        dark:focus-visible:outline-primary-dark" rows="3" placeholder="Coloca la informacion"></textarea>
    </td>
  </tr>
</table>
<br>
@endif


        <br>
        <button type="submit" class="inline-flex justify-center items-center gap-2 rounded-radius bg-success border border-success px-4 py-2 text-sm font-medium text-on-success transition hover:opacity-75">
            Guardar
        </button>
</div> 