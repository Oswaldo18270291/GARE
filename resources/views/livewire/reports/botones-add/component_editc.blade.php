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
    @if (session('cont'))
    <div x-data="{ alertIsVisible: true }" x-show="alertIsVisible" class="relative w-full overflow-hidden rounded-radius border border-success bg-surface text-on-surface dark:bg-surface-dark dark:text-on-surface-dark" role="alert" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
        <div class="flex w-full items-center gap-2 bg-success/10 p-4">
            <div class="bg-success/15 text-success rounded-full p-1" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-6" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-2">
                <h3 class="text-sm font-semibold text-success">Mensaje de información</h3>
                <p class="text-xs font-medium sm:text-sm">{{ session('cont') }}</p>
            </div>
            <button type="button" @click="alertIsVisible = false" class="ml-auto" aria-label="dismiss alert">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="2.5" class="w-4 h-4 shrink-0">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
    @endif
@if($boton=='sub')
  @if ($titulo==14)
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
        <td style="border: 1px solid black; padding: 5px;">Factor de ocurrencia</td>
        <td style="border: 1px solid black; padding: 5px;">Acciones</td>
      </tr>

      @foreach (collect($riesgos)->sortBy('no') as $i => $riesgo)
        <tr wire:key="riesgo-{{ $i }}">
          {{-- No (solo lectura; se renumera automático) --}}
          <td style="border: 1px solid black;">{{ $riesgo['no'] }}</td>

          {{-- Nombre del riesgo (editable) --}}
          <td style="border: 1px solid black; text-align: left;">
            <input type="text" wire:model.live="riesgos.{{ $i }}.riesgo" class="w-full border px-2 py-1 text-left">
            @error("riesgos.$i.riesgo") <div class="text-red-600 text-xs">{{ $message }}</div> @enderror
          </td>

          {{-- Campos editables numéricos --}}
          @foreach (['f','s','p','e','pb','if'] as $col)
            <td style="border: 1px solid black;">
              <input type="number" min="1" max="5" oninput="this.value = Math.max(1, Math.min(5, this.value))" wire:model.live="riesgos.{{ $i }}.{{ $col }}" class="w-12 text-center border">
              @error("riesgos.$i.$col") <div class="text-red-600 text-xs">{{ $message }}</div> @enderror
            </td>
          @endforeach

          {{-- Clase de riesgo --}}
          @php
            $bg = $this->colorRiesgo($riesgo);
            $fg = $this->colorTexto($riesgo);
            $clase = $this->claseRiesgo($riesgo);
            $factor = number_format($this->calcularFOcurrencia($riesgo), 2);
          @endphp
          <td style="border: 1px solid black; background-color: {{ $bg }}; color: {{ $fg }}; font-weight: bold;">
            {{ $clase }}
          </td>

          {{-- Factor --}}
          <td style="border: 1px solid black;">{{ $factor }}</td>

          {{-- Acciones fila --}}
          <td style="border: 1px solid black;">
            <button type="button" wire:confirm="¿Estás seguro de eliminar esta fila? \nEsta acción no se puede deshacer despues de darle aceptar."
          wire:click="removeFila({{ $i }})" class="px-2 py-1 bg-red-600 text-white rounded">Eliminar</button>
          </td>
        </tr>
      @endforeach
    </table>

  <div class="mt-3 flex gap-2">
      <button type="button" wire:click="addFila" class="px-3 py-2 bg-emerald-600 text-white rounded">+ Agregar fila</button>

      <button type="button" 
              wire:click="updateRiesgos({{ $content->id ?? 'null' }})" 
              class="px-3 py-2 bg-blue-600 text-white rounded">
          💾 Guardar cambios de tabla
      </button>
  </div>
  @endif
  @if ($titulo==15)
  <br>
  <style>
      table {
        width: 100%;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
        font-size: 11pt;
      }
      td {
        border: 1px solid black;
        padding: 6px;
      }
      tr.dragging {
        background-color: #d1ecf1 !important;
        opacity: 0.8;
      }
      tr.placeholder {
        background: #f8d7da !important;
      }
    </style>
  <div>
    @if(!empty($risks))
      <table id="tabla">
          <!-- Riesgos iniciales (arriba de todo) -->
          <tr style="background-color: #ffc107; font-weight: bold;">
              <td colspan="2">Riesgos sin clasificar</td>
          </tr>
          <tbody id="pendientes" wire:ignore>
              @foreach ($risks->where('tipo_riesgo', 'pendientes')->sortBy('orden') as $r)
                  <tr data-id="{{ $r->id }}">
                      <td style="width: 40px; text-align: center;">{{ $r->orden }}</td>
                      <td>{{ $r->no }} - {{ $r->riesgo }}</td>
                  </tr>
              @endforeach
          </tbody>

          <!-- Encabezado Cibernéticos -->
          <tr style="background-color: #0f4a75ff; font-weight: bold; color:white;">
              <td colspan="2">Cibernéticos</td>
          </tr>
          <tbody id="ciberneticos" wire:ignore>
              @foreach ($risks->where('tipo_riesgo', 'ciberneticos')->sortBy('orden') as $r)
                  <tr data-id="{{ $r->id }}">
                      <td style="width: 40px; text-align: center;">{{ $r->orden }}</td>
                      <td>{{ $r->no }} - {{ $r->riesgo }}</td>
                  </tr>
              @endforeach
          </tbody>

          <!-- Encabezado Naturales -->
          <tr style="background-color: #0f4a75ff; font-weight: bold; color:white;">
              <td colspan="2">Naturales</td>
          </tr>
          <tbody id="naturales" wire:ignore>
              @foreach ($risks->where('tipo_riesgo', 'naturales')->sortBy('orden') as $r)
                  <tr data-id="{{ $r->id }}">
                      <td style="width: 40px; text-align: center;">{{ $r->orden }}</td>
                      <td>{{ $r->no }} - {{ $r->riesgo }}</td>
                  </tr>
              @endforeach
          </tbody>

          <!-- Encabezado Sociales -->
          <tr style="background-color: #0f4a75ff; font-weight: bold; color: white">
              <td colspan="2">Sociales (Personas)</td>
          </tr>
          <tbody id="sociales" wire:ignore>
              @foreach ($risks->where('tipo_riesgo', 'sociales')->sortBy('orden') as $r)
                  <tr data-id="{{ $r->id }}">
                      <td style="width: 40px; text-align: center;">{{ $r->orden }}</td>
                      <td>{{ $r->no }} - {{ $r->riesgo }}</td>
                  </tr>
              @endforeach
          </tbody>
      </table>
  </div>

  <script>
      function actualizarNumerosYGuardar() {
          let data = [];

          const mapSecciones = {
              pendientes: "pendientes",
              ciberneticos: "ciberneticos",
              naturales: "naturales",
              sociales: "sociales"
          };

          ["pendientes", "ciberneticos", "naturales", "sociales"].forEach(id => {
              const tbody = document.getElementById(id);
              const filas = tbody.querySelectorAll("tr");

              filas.forEach((fila, index) => {
                  fila.querySelector("td:first-child").textContent = index + 1;

                  data.push({
                      id: fila.getAttribute("data-id"),
                      orden: index + 1,
                      tipo_riesgo: mapSecciones[id]
                  });
              });
          });

          // Enviar a Livewire
          Livewire.dispatch("guardarOrden", { risks: data });
      }

      ["pendientes", "ciberneticos", "naturales", "sociales"].forEach(id => {
          new Sortable(document.getElementById(id), {
              group: "riesgos",
              animation: 150,
              ghostClass: "dragging",
              onEnd: actualizarNumerosYGuardar
          });
      });
  </script>
      @else
        <table id="tabla">
          <tr style="background-color: #ffc107; font-weight: bold; text-align: center;">
              <td colspan="2">INFORMACIÓN</td>
          </tr>
          <tr>
              <td style="width: 40px; text-align: center;">
                  No hay riesgos disponibles. Por favor, agregue riesgos en el apartado de "Mosler: Informe".
              </td>
        </table>
      @endif

  @endif
  @if ($titulo==16)
  GRAFICA
  <h4>4.1.3 Nivel de Riesgo - Gráfico de Consecuencia x Factor de Ocurrencia</h4>
@if(!empty($risks))

  <div class="relative flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
      <label for="chartType" class="w-fit pl-0.5 text-sm">Tipo de gráfico:</label>
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="absolute pointer-events-none right-4 top-8 size-5">
          <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
      </svg>

      <select 
          wire:model="grafica" 
          id="chartType" 
          name="chartType" 
          class="w-full appearance-none rounded-radius border border-outline bg-surface-alt px-4 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
      >
          <option value="bar">Barras</option>
          <option value="pie">Pastel</option>
          <option value="doughnut">Dona</option>
          <option value="polarArea">Área polar</option>
      </select>
  </div>
  <!-- Contenedor del gráfico -->
  <canvas id="riesgosChart" width="800" height="400"></canvas>


  <script>
    const ctx = document.getElementById('riesgosChart').getContext('2d');
    const chartTypeSelect = document.getElementById('chartType');

    const riesgos = @json($risks->sortBy('no')->map(fn($r) => $r->no . ' - ' . $r->riesgo)->values());
    const riesg = @json($risks->sortBy('no')->map(fn($r) => $r->no)->values());
    const ocurrencias = @json($risks->sortBy('no')->pluck('f_ocurrencia')->values());
    const tipoInicial = @json($grafica); // 👈 tipo de gráfica desde base de datos

    const colores = ocurrencias.map(v => {
      if (v >= 80) return "rgba(206, 0, 0, 0.9)";
      if (v >= 60) return "rgba(235, 231, 0, 0.9)";
      if (v >= 40) return "rgba(4, 121, 0, 0.9)";
      return "rgba(102, 209, 98, 0.9)";
    });

    function crearGrafico(tipo) {
      if (window.chart) window.chart.destroy();

      const esCircular = ['pie', 'doughnut', 'polarArea'].includes(tipo);

      // 🔹 Generamos los datasets individuales
    const dataConfig = esCircular
      ? {
          labels: riesgos,
          labe: riesg,
          datasets: [{
            label: 'Factor de ocurrencia',
            data: ocurrencias,
            backgroundColor: colores
          }]
        }
      : {
          // En gráficas de barras, cada riesgo será su propio dataset
          labels: ['Factor de ocurrencia'], // eje X genérico
          datasets: riesgos.map((nombre, i) => ({
            label: nombre,                // nombre del riesgo
            data: [ocurrencias[i]],       // valor del riesgo
            backgroundColor: colores[i] ,
            numero: riesg[i],  
          }))
        };

      window.chart = new Chart(ctx, {
        type: tipo,
        data: dataConfig,
        options: {
          responsive: true,
          plugins: {
            legend: { display: true,
              position: 'bottom',
              labels: {
                color: '#000',
                font: { size: 11, weight: 'bold' },
                boxWidth: 15,
                padding: 8
              },
            },
            
            datalabels: {
              color: '#000',
              anchor: tipo === 'bar' ? 'end' : 'center',
              align: tipo === 'bar' ? 'end' : 'center',
              font: { weight: 'bold', size: 10 },
              formatter: (value, ctx) => {
                if (esCircular) {
                  const index = ctx.dataIndex;              
                  return `${ctx.chart.data.labe[index]}\n(${value})`;
                } else {
                  // En barras, mostramos nombre + valor del dataset
                  const dataset = ctx.chart.data.datasets[ctx.datasetIndex];
                  return `${dataset.numero} (${value})`;
                }
              }
            }
          },
          // 🔹 Escalas solo para gráficas no circulares
        scales: esCircular ? {} : {
          x: { 
            ticks: { color: '#000' },
            grid: { display: false }
          },
          y: { 
            beginAtZero: true,
            ticks: { color: '#000' },
            grid: { color: '#ddd' },
            max: 100
          }
        }
      },
        plugins: [ChartDataLabels]
      });
    }

    // 👇 Establecer el tipo del select al valor guardado
    chartTypeSelect.value = tipoInicial ?? 'bar';

    // 👇 Crear gráfico con el tipo guardado en la BD
    crearGrafico(tipoInicial ?? 'bar');

    // 👇 Detectar cambio manual
    chartTypeSelect.addEventListener('change', (e) => {
      crearGrafico(e.target.value);
    });

    // 👇 Escuchar actualizaciones desde Livewire
    document.addEventListener('livewire:update', () => {
      const nuevoTipo = @this.grafica;
      chartTypeSelect.value = nuevoTipo;
      crearGrafico(nuevoTipo);
    });
  </script>
  <script>
  document.addEventListener('livewire:load', function () {
      // 🔁 Espera a que Livewire cargue y refresca el componente automáticamente
      Livewire.dispatch('refreshChart');
  });
  </script>


  Características del Riesgo.
  <div>
    <table id="tabla" style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 10pt; text-align: left;">
          <!-- Pendientes -->
      <tr style="background-color: #ffc107; font-weight: bold; text-align: center;">
        <td colspan="3">Pendientes</td>
      </tr>
      <tr>
        <td colspan="3" style="border: 1px solid black; padding: 6px;">
          <ul id="pendientes" wire:ignore style="list-style: none; padding: 0; margin: 0; min-height: 80px;">
            @foreach ($risks->where('c_riesgo', 'pendientes')->sortBy('orden2') as $r)
              <li data-id="{{ $r->id }}" style="border: 1px solid #999; margin: 4px 0; padding: 4px; background: #fffbe6; border-radius: 4px;">
                <strong>{{ $r->orden2 }}</strong>. {{ $r->no }} - {{ $r->riesgo }}
              </li>
            @endforeach
          </ul>
        </td>
      </tr>
      <!-- Encabezados -->
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

      <!-- Celdas con listas (drag & drop por columna) -->
      <tr style="vertical-align: top;">
        <!-- Columna Normal -->
        <td style="border: 1px solid black; padding: 6px; height: 250px;">
          <ul id="normal" wire:ignore style="list-style: none; padding: 0; margin: 0; min-height: 200px;">
            @foreach ($risks->where('c_riesgo', 'normal')->sortBy('orden2') as $r)
              <li data-id="{{ $r->id }}" style="border: 1px solid #999; margin: 4px 0; padding: 4px; background: #eaf9e8; border-radius: 4px;">
                <strong>{{ $r->orden2 }}</strong>. {{ $r->no }} - {{ $r->riesgo }}
              </li>
            @endforeach
          </ul>
        </td>

        <!-- Columna Intermedio -->
        <td style="border: 1px solid black; padding: 6px; height: 250px;">
          <ul id="intermedio" wire:ignore style="list-style: none; padding: 0; margin: 0; min-height: 200px;">
            @foreach ($risks->where('c_riesgo', 'intermedio')->sortBy('orden2') as $r)
              <li data-id="{{ $r->id }}" style="border: 1px solid #999; margin: 4px 0; padding: 4px; background: #fffbd1; border-radius: 4px;">
                <strong>{{ $r->orden2 }}</strong>. {{ $r->no }} - {{ $r->riesgo }}
              </li>
            @endforeach
          </ul>
        </td>

        <!-- Columna Inmediato -->
        <td style="border: 1px solid black; padding: 6px; height: 250px;">
          <ul id="inmediato" wire:ignore style="list-style: none; padding: 0; margin: 0; min-height: 200px;">
            @foreach ($risks->where('c_riesgo', 'inmediato')->sortBy('orden2') as $r)
              <li data-id="{{ $r->id }}" style="border: 1px solid #999; margin: 4px 0; padding: 4px; background: #ffdede; border-radius: 4px;">
                <strong>{{ $r->orden2 }}</strong>. {{ $r->no }} - {{ $r->riesgo }}
              </li>
            @endforeach
          </ul>
        </td>
      </tr>



      <!-- Descripciones -->
      <tr style="vertical-align: top;">
        <td style="width: 33.33%; border: 1px solid black; padding: 6px; background-color: #00B050; color: black;">
          Este rango representa riesgos de baja probabilidad y bajo impacto. Los eventos situados en este rango 
          normalmente se consideran aceptables y dentro de los límites normales de operación. Las consecuencias, 
          si ocurren, serían bajas y fácilmente controladas por la organización. Normalmente, no se necesita ninguna 
          acción correctiva inmediata, pero se deben mantener los controles actuales y monitorear continuamente los riesgos 
          para garantizar que permanezcan dentro de esta zona de seguridad. Este rango representa riesgos de baja probabilidad y 
          bajo impacto. Los eventos situados en este rango normalmente se consideran aceptables y dentro de los límites normales de 
          operación. Las consecuencias, si ocurren, serían bajas y fácilmente controladas por la organización. Normalmente, no se necesita 
          ninguna acción correctiva inmediata, pero se deben mantener los controles actuales y monitorear continuamente los riesgos para garantizar 
          que permanezcan dentro de esta zona de seguridad.
        </td>

        <td style="width: 33.33%; border: 1px solid black; padding: 6px; background-color: #FFFF00; color: black;">
          En este rango, los riesgos presentan una probabilidad y/o impactos moderados. Los eventos en el área intermedia requieren atención, 
          ya que pueden causar perturbaciones significativas en la operación, aunque no de manera catastrófica. Se recomiendan medidas preventivas 
          o correctivas para mitigar el impacto o la probabilidad de ocurrencia, con un monitoreo constante para evitar que migren al área de 
          riesgo intolerable.
        </td>

        <td style="width: 33.33%; border: 1px solid black; padding: 6px; background-color: #FF0000; color: white;">
          Este rango representa riesgos de alta probabilidad y/o alto impacto, siendo considerados inaceptables y 
          requieren intervención inmediata. Cualquier evento en este rango puede causar graves consecuencias para la 
          organización, comprometiendo seriamente sus objetivos y/o procesos. La mitigación de estos riesgos debe ser 
          la máxima prioridad, y se requieren acciones inmediatas para reducir el impacto y/o la probabilidad de ocurrencia.
        </td>
      </tr>
    </table>
  </div>

  <script>
    function actualizarNumerosYGuardar2() {
      let data = [];
      const mapSecciones = {
        pendientes: "pendientes",
        normal: "normal",
        intermedio: "intermedio",
        inmediato: "inmediato"
      };

      ["pendientes", "normal", "intermedio", "inmediato"].forEach(id => {
        const lista = document.getElementById(id);
        const items = lista.querySelectorAll("li");

        items.forEach((item, index) => {
          // Actualiza visualmente el número
          const numero = item.querySelector("strong");
          if (numero) numero.textContent = index + 1;

          data.push({
            id: item.getAttribute("data-id"),
            orden2: index + 1,
            c_riesgo: mapSecciones[id]
          });
        });
      });

      Livewire.dispatch("guardarOrden2", { risks: data });
    }

    ["pendientes", "normal", "intermedio", "inmediato"].forEach(id => {
      new Sortable(document.getElementById(id), {
        group: "riesgos",
        animation: 150,
        ghostClass: "dragging",
        onEnd: actualizarNumerosYGuardar2
      });
    });
  </script>
      @else
        <style>
      table {
        width: 100%;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
        font-size: 11pt;
      }
      td {
        border: 1px solid black;
        padding: 6px;
      }
      tr.dragging {
        background-color: #d1ecf1 !important;
        opacity: 0.8;
      }
      tr.placeholder {
        background: #f8d7da !important;
      }
    </style>
        <table id="tabla">
          <tr style="background-color: #ffc107; font-weight: bold; text-align: center;">
              <td colspan="2">INFORMACIÓN</td>
          </tr>
          <tr>
              <td style="width: 40px; text-align: center;">
                  No hay riesgos disponibles. Por favor, agregue riesgos en el apartado de "Mosler: Informe".
              </td>
        </table>
      @endif


  <br>
  @endif
  @if ($titulo==17)
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
  @if ($titulo==18)
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
  @if (in_array($titulo, [20,21,22,23,24,25,26,27,28,29]))
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
@endif
    <br>
    <button type="submit" class="inline-flex justify-center items-center gap-2 rounded-radius bg-success border border-success px-4 py-2 text-sm font-medium text-on-success transition hover:opacity-75">
        Guardar
    </button>
</div> 