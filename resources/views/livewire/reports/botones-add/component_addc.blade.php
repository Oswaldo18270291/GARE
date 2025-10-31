<div>    
    <!-- Bloque Quill -->
    <div 
    x-data 
    wire:ignore
    x-init="
        const init = () => {
            if (typeof Quill === 'undefined') { return setTimeout(init, 150); }

            const quill = new Quill($refs.editorTit, {
                theme: 'snow',
                modules: {
                    toolbar: {
                        container: [
                            [{ header: [1, 2, false] }],
                            ['bold', 'italic', 'underline'],
                            [{ 'align': [] }],
                            [{ list: 'ordered' }, { list: 'bullet' }],
                            ['clean']
                        ]
                    }
                }
            });

            const toolbar = quill.getModule('toolbar').container;
            const editor  = quill.root;

            // --- Botón 'a.' (lista alfabética)
            const alphaButton = document.createElement('button');
            alphaButton.innerHTML = `
                <svg viewBox='0 0 18 18'>
                  <text x='4' y='14' font-size='13' font-family='Arial'>a.</text>
                </svg>`;
            alphaButton.type = 'button';
            alphaButton.classList.add('ql-alpha');
            alphaButton.title = 'Lista alfabética';

            const orderedBtn = toolbar.querySelector('.ql-list[value=ordered]');
            orderedBtn?.insertAdjacentElement('afterend', alphaButton);

            // Al hacer clic en 'a.' -> forzar alfabética
            alphaButton.addEventListener('click', () => {
                const range = quill.getSelection();
                if (!range) return;

                const fmt = quill.getFormat(range);
                // si no es alfabética, convertir a ordenada y activar alpha-list
                if (!fmt.list || editor.classList.contains('alpha-list') === false) {
                    quill.format('list', 'ordered');
                    editor.classList.add('alpha-list');
                    alphaButton.classList.add('ql-active');
                } else {
                    // si ya está con alpha-list, quitar la lista
                    quill.format('list', false);
                    editor.classList.remove('alpha-list');
                    alphaButton.classList.remove('ql-active');
                }
            });

            // Al hacer clic en el botón numerado -> quitar alpha-list
            orderedBtn?.addEventListener('click', () => {
                // Deja que Quill aplique/toggle la lista ordenada…
                // y nosotros limpiamos la clase para volver a números
                editor.classList.remove('alpha-list');
                alphaButton.classList.remove('ql-active');
            });

            // También si cambia el formato y queda 'ordered', limpiamos alpha-list
            quill.on('editor-change', () => {
                const range = quill.getSelection();
                if (!range) return;
                const fmt = quill.getFormat(range);
                if (fmt.list === 'ordered') {
                    editor.classList.remove('alpha-list');
                    alphaButton.classList.remove('ql-active');
                }
            });

            // CSS: números por defecto, letras cuando alpha-list está activo
            const style = document.createElement('style');
            style.innerHTML = `
              .ql-toolbar button.ql-alpha svg { width: 18px; height: 18px; }
              /* numeración normal (decimal) por defecto */
              .ql-editor ol > li::before { content: counter(list-0, decimal) '. '; }
              /* alfabético cuando está activa la clase */
              .ql-editor.alpha-list ol > li::before { content: counter(list-0, lower-alpha) '. '; }
              .ql-editor.alpha-list ol ol > li::before { content: counter(list-1, lower-alpha) '. '; }
              .ql-editor.alpha-list ol ol ol > li::before { content: counter(list-2, lower-alpha) '. '; }
              .ql-editor.alpha-list ol ol ol ol > li::before { content: counter(list-3, lower-alpha) '. '; }
              .ql-editor.alpha-list ol ol ol ol ol > li::before { content: counter(list-4, lower-alpha) '. '; }
            `;
            document.head.appendChild(style);

            // Sincroniza con Livewire
            quill.on('text-change', () => {
                $refs.textareaTit.value = quill.root.innerHTML;
                $refs.textareaTit.dispatchEvent(new Event('input'));
            });
        };
        init();
    "
>
    <div x-ref="editorTit" style="height:200px; background:white;"></div>
    <textarea x-ref="textareaTit" wire:model="contenido" class="hidden"></textarea>
</div>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <br>
<div class="flex gap-6 flex-wrap">

    <!-- Grupo Imagen 1 + Leyenda -->
    
    <div class="flex flex-col items-center w-full max-w-xs">
      <div class="flex w-full flex-col gap-1 mt-2">            
        <label class="w-fit pl-0.5 text-2x1">Título 1</label>
        <input wire:model="leyenda1" id="leyenda1" type="text"
               class="bg-white w-full rounded-radius border border-outline px-2 py-2 text-sm"
               placeholder="Ingrese texto del título 1"
               style="border-color:rgba(31, 89, 177, 1);" />
            @error('leyenda1') 
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
    </div>
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
</div>


    <!-- Grupo Imagen 2 + Leyenda -->
  <div class="flex flex-col items-center w-full max-w-xs">
    <div class="flex w-full flex-col gap-1 mt-2">
        <label class="w-fit pl-0.5 text-2x1">Título 2</label>
        <input wire:model="leyenda2" id="leyenda2" type="text"
               class="bg-white w-full rounded-radius border border-outline px-2 py-2 text-sm"
               placeholder="Ingrese texto del título 2"
               style="border-color:rgba(31, 89, 177, 1);" />
            @error('leyenda2') 
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
    </div>
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
</div>


    <!-- Grupo Imagen 3 + Leyenda -->
  <div class="flex flex-col items-center w-full max-w-xs">
    <div class="flex w-full flex-col gap-1 mt-2">
        <label class="w-fit pl-0.5 text-2x1">Título 3</label>
        <input wire:model="leyenda3" id="leyenda3" type="text"
               class="bg-white w-full rounded-radius border border-outline px-2 py-2 text-sm"
               placeholder="Ingrese texto del título 3"
               style="border-color:rgba(31, 89, 177, 1);" />
            @error('leyenda3') 
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror               
    </div>
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
</div>
</div>
<br>
@if ($boton=='sub')
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

      @foreach ($riesgos as $i => $riesgo)
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
            <button type="button" wire:click="removeFila({{ $i }})" class="px-2 py-1 bg-red-600 text-white rounded">Eliminar</button>
          </td>
        </tr>
      @endforeach
    </table>

    <div class="mt-3 flex gap-2">
      <button type="button" wire:click="addFila" class="px-3 py-2 bg-emerald-600 text-white rounded">+ Agregar fila</button>
    </div>
  @endif
  @if($titulo==32)

<div>
    @if (session('success'))
        <div class="bg-green-200 text-green-800 p-2 rounded mb-3">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full border-collapse text-center text-sm font-sans" style="border:1px solid black;">
        <thead>
            <tr style="background-color:#002060; color:white;">
                <th rowspan="2" class="border p-2">No.</th>
                <th rowspan="2" class="border p-2">Tipo de Riesgo</th>
                <th colspan="5" class="border p-2">Criterios de Evaluación</th>
                <th rowspan="2" class="border p-2">Total<br>Posible</th>
                <th rowspan="2" class="border p-2">Cal.</th>
                <th rowspan="2" class="border p-2">Clase de Riesgo</th>
                <th rowspan="2" class="border p-2">Factor de ocurrencia<br>del riesgo</th>
            </tr>
            <tr style="background-color:#002060; color:white;">
                <th class="border p-1">Impacto en las Funciones</th>
                <th class="border p-1">Impacto en la Organización</th>
                <th class="border p-1">Extensión del Daño</th>
                <th class="border p-1">Probabilidad de Materialización</th>
                <th class="border p-1">Impacto Financiero</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($riesgos as $i => $r)
                <tr>
                    <td class="border p-1">{{ $r['no'] }}</td>
                    <td class="border p-1 text-left">{{ $r['riesgo'] }}</td>

                    @foreach (['impacto_f','impacto_o','extension_d','probabilidad_m','impacto_fin'] as $campo)
                        <td class="border p-1">
                          <input 
                              type="number"
                              min="1"
                              max="5"
                              step="1"
                              required
                              oninput="this.value = Math.max(1, Math.min(5, this.value))"
                              wire:model.lazy="riesgos.{{ $i }}.{{ $campo }}"
                              wire:blur="recalcularRiesgosFila({{ $i }})"                              
                              class="w-14 text-center border-gray-400 rounded"
                          />
                        </td>
                    @endforeach

                    <td class="border p-1">25</td>
                    <td class="border p-1 font-semibold">{{ $r['cal'] ?? '' }}</td>
                    <td class="border p-1 font-bold text-white"
                        style="background-color:
                            {{ ($r['clase_riesgo'] ?? '') == 'MUY ALTO' ? '#ff0000' :
                               (($r['clase_riesgo'] ?? '') == 'ALTO' ? '#ff6600' :
                               (($r['clase_riesgo'] ?? '') == 'MEDIO' ? '#ffc000' :
                               (($r['clase_riesgo'] ?? '') == 'BAJO' ? '#00b050' : 'transparent'))) }}">
                        {{ $r['clase_riesgo'] ?? '' }}
                    </td>
                    <td class="border p-1">{{ $r['factor_oc'] ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<br>
  <h4>4.1.3 Nivel de Riesgo - Gráfico de Consecuencia x Factor de Ocurrencia</h4>

<div class="relative flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
    <label for="chartType" class="w-fit pl-0.5 text-sm">Tipo de gráfico:</label>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="absolute pointer-events-none right-4 top-8 size-5">
        <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
    </svg>
    <select 
        required
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
<br>
<br>
<!-- Contenedor del gráfico -->
<div wire:ignore>
    <canvas id="riesgosChart" width="800" height="400"></canvas>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', inicializarGrafica);
document.addEventListener('livewire:navigated', () => setTimeout(inicializarGrafica, 100));
if (window.Livewire) {
    Livewire.hook('morph.updated', () => setTimeout(inicializarGrafica, 100));
}

function inicializarGrafica() {
    let chartInstance = null;
    const canvas = document.getElementById('riesgosChart');
    const select = document.getElementById('chartType');
    if (!canvas || !select) return; // evitar errores si no existe

    const ctx = canvas.getContext('2d');

    function crearGrafico(riesgos = [], tipo = 'bar') {
        if (!Array.isArray(riesgos)) return;
        if (chartInstance) chartInstance.destroy();

        const etiquetas = riesgos.map(r => `${r.no} - ${r.riesgo}`);
        const numeros = riesgos.map(r => r.no);
        const ocurrencias = riesgos.map(r => parseFloat(r.factor_oc) || 0);

        const colores = ocurrencias.map(v => {
            if (v >= 80) return "rgba(206, 0, 0, 0.9)";
            if (v >= 60) return "rgba(235, 231, 0, 0.9)";
            if (v >= 40) return "rgba(4, 121, 0, 0.9)";
            return "rgba(102, 209, 98, 0.9)";
        });

        const esCircular = ['pie', 'doughnut', 'polarArea'].includes(tipo);

        const dataConfig = esCircular
            ? {
                labels: etiquetas,
                labe: numeros,
                datasets: [{
                    label: 'Factor de ocurrencia',
                    data: ocurrencias,
                    backgroundColor: colores
                }]
            }
            : {
                labels: ['Factor de ocurrencia'],
                datasets: etiquetas.map((nombre, i) => ({
                    label: nombre,
                    data: [ocurrencias[i]],
                    backgroundColor: colores[i],
                    numero: numeros[i],
                }))
            };

        Chart.register(ChartDataLabels);

        chartInstance = new Chart(ctx, {
            type: tipo,
            data: dataConfig,
            options: {
                responsive: false,
                maintainAspectRatio: false,
                animation: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            color: '#000',
                            font: { size: 12, weight: 'bold' },
                            boxWidth: 15,
                            padding: 8
                        }
                    },
                    datalabels: {
                        display: true,
                        color: '#000',
                        anchor: tipo === 'bar' ? 'end' : 'center',
                        align: tipo === 'bar' ? 'end' : 'center',
                        font: { weight: 'bold', size: 10 },
                        formatter: (value, ctx) => {
                            if (esCircular) {
                                const index = ctx.dataIndex;
                                return `${ctx.chart.data.labe[index]}\n(${value})`;
                            } else {
                                const dataset = ctx.chart.data.datasets[ctx.datasetIndex];
                                return `${dataset.numero} (${value})`;
                            }
                        }
                    }
                },
                scales: esCircular ? {} : {
                    y: { beginAtZero: true, max: 120, ticks: { color: '#000' } },
                    x: { ticks: { color: '#000' }, grid: { display: false } }
                }
            },
            plugins: [ChartDataLabels]
        });

        window.ultimoDataGrafica = riesgos;
    }

    // 🔁 Cambio de tipo manual
    select.addEventListener('change', e => {
        const tipo = e.target.value;
        if (window.ultimoDataGrafica) {
            crearGrafico(window.ultimoDataGrafica, tipo);
        }
    });

    // 🧠 Evento Livewire que actualiza los datos
    Livewire.on('actualizarGrafica', payload => {
        const data = Array.isArray(payload) ? payload[0] : payload;
        if (!data || !data.riesgos) return;
        window.ultimoDataGrafica = data.riesgos;
        const tipo = select.value || 'bar';
        crearGrafico(data.riesgos, tipo);
    });

    crearGrafico([], select.value || 'bar');
}

</script>
@endpush

<div class="p-4">
    <div class="mb-6 flex items-center justify-center p-4 rounded-lg" style="background-color: rgba(39, 68, 112, 1);">
        <h1 class=" text-white font-sans font-bond text-lg">MAPA MENTAL - INTERACCIÓN DE RIESGOS</h1>
        </div>

    {{-- Agregar nodo --}}
    <div class="flex flex-wrap justify-center gap-3 mb-3">
        <input type="text" wire:model.defer="nuevoNodo"
               placeholder="Nombre del nodo"
               class="border rounded px-2 py-1 text-sm focus:ring focus:ring-blue-300 w-40"
               style="border-color:rgba(31, 89, 177, 1);">

        <label class="flex items-center gap-1 text-sm">
            Fondo:
            <input type="color" wire:model="colorNodo" class="w-8 h-8 border rounded" style="border-color:rgba(31, 89, 177, 1);">
        </label>

        <label class="flex items-center gap-1 text-sm">
            Letra:
            <input type="color" wire:model="colorLetra" class="w-8 h-8 border rounded" style="border-color:rgba(31, 89, 177, 1);">
        </label>

        <label class="flex items-center gap-1 text-sm">
            Tamaño:
            <input type="number" wire:model="tamanoLetra" min="8" max="30"
                   class="w-16 border rounded px-1 py-0.5 text-center text-sm"
                   style="border-color:rgba(31, 89, 177, 1);">
        </label>

        <button type="button"
                wire:click="agregarNodoDesdeFront"
                class="bg-sky-700 text-white px-3 py-1 rounded hover:bg-sky-800">
            Agregar Nodo
        </button>
    </div>

    {{-- Conectar nodos --}}
    <div class="flex flex-wrap justify-center gap-3 mb-3">
        <select wire:model="nodoDesde" class="border px-2 py-1 rounded text-sm" style="border-color:rgba(31, 89, 177, 1);">
            <option value="">Desde...</option>
            @foreach ($nodos as $n)
                <option value="{{ $n['id'] }}">{{ $n['label'] }}</option>
            @endforeach
        </select>

        <select wire:model="nodoHasta" class="border px-2 py-1 rounded text-sm" style="border-color:rgba(31, 89, 177, 1);">
            <option value="">Hasta...</option>
            @foreach ($nodos as $n)
                <option value="{{ $n['id'] }}">{{ $n['label'] }}</option>
            @endforeach
        </select>

        <button type="button"
                wire:click="conectarNodos"
                class="bg-sky-700 text-white px-3 py-1 rounded hover:bg-sky-800">
            Conectar
        </button>
        {{-- Botones de eliminación --}}
        <div class="flex justify-center gap-3 mb-4">
            <button id="btnEliminarNodo"
                    class="bg-red-800 text-white px-3 py-1 rounded hover:bg-red-800 disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="40"
                      height="40"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="#ffffffff"
                      stroke-width="0.75"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <title>Eliminar Nodo Seleccionado</title>
                      <path d="M9 12h6" />
                      <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
                    </svg>

            </button>

            <button id="btnEliminarConexion"
                    class="text-white px-3 py-1 rounded hover:bg-orange-800 disabled:opacity-50 disabled:cursor-not-allowed"
                    style="background-color:rgba(219, 91, 32, 1);"
                    disabled>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="40"
                      height="40"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="#ffffffff"
                      stroke-width="0.75"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <title>Eliminar Conexión Seleccionada</title>
                      <path d="M6 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                      <path d="M18 6m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                      <path d="M15.861 15.896a3 3 0 0 0 4.265 4.22m.578 -3.417a3.012 3.012 0 0 0 -1.507 -1.45" />
                      <path d="M8.7 10.7l1.336 -.688m2.624 -1.352l2.64 -1.36" />
                      <path d="M8.7 13.3l6.6 3.4" />
                      <path d="M3 3l18 18" />
                    </svg>

            </button>
            <label class="flex flex-col text-sm text-black">
                <span class="mb-1">Fondo del mapa:</span>
                <input 
                    type="file" 
                    id="inputFondo" 
                    accept="image/*"
                    class="text-gray-500 text-sm border rounded p-1 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-400"
                    style="border-color:rgba(31, 89, 177, 1);"
                >
            </label>
            <button id="btnQuitarFondo"
                    type="button"
                    class="bg-gray-600 text-white px-3 py-1 rounded hover:bg-gray-800 disabled:opacity-50">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="40"
                      height="Auto"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="#ffffffff"
                      stroke-width="1"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <title>Quitar fondo</title>
                      <path d="M15 8h.01" />
                      <path d="M13 21h-7a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v7" />
                      <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l3 3" />
                      <path d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0" />
                      <path d="M22 22l-5 -5" />
                      <path d="M17 22l5 -5" />
                    </svg>
            </button>
        </div>
    </div>
<label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
    Opacidad:
    <input type="range" id="rangoOpacidad" min="0" max="100" value="40"
           class="cursor-pointer w-32 accent-blue-700">
</label>
    {{-- Contenedor del mapa --}}
<div class="relative w-full h-[600px] rounded-lg border border-gray-300 overflow-hidden" wire:ignore style="border-color:rgba(31, 89, 177, 1);">
    {{-- Fondo con imagen y opacidad --}}
    <div id="network-bg"
         class="absolute inset-0 bg-gray-100 bg-center bg-cover bg-no-repeat transition-all duration-500"
         style="opacity: 0.4;">
    </div>

    {{-- Canvas del mapa --}}
    <div id="network" class="absolute inset-0 w-full h-full"></div>
</div>

@once
<script src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>
@endonce

<script>
function renderMapa() {
    console.log("🚀 Renderizando mapa mental (versión 2 adaptada)");

    const container = document.getElementById('network');
    const networkBackground = document.getElementById('network-bg');
    const btnEliminarNodo = document.getElementById('btnEliminarNodo');
    const btnEliminarConexion = document.getElementById('btnEliminarConexion');
    const inputFondo = document.getElementById('inputFondo');
    const btnQuitarFondo = document.getElementById('btnQuitarFondo');
    const rangoOpacidad = document.getElementById('rangoOpacidad');

    if (!container) {
        console.warn("⚠️ No se encontró el contenedor #network.");
        return;
    }
    if (typeof vis === 'undefined') {
        console.error("❌ Vis.js no está disponible todavía.");
        return;
    }

    // 🚫 Evita redibujar si ya se está renderizando
    if (window.__renderingMapa) return;
    window.__renderingMapa = true;
    setTimeout(() => window.__renderingMapa = false, 500);

    // === Datos del backend ===
    const nodos = @json($nodos ?? []);
    const relaciones = @json($relaciones ?? []);

    const nodes = new vis.DataSet(nodos);
    const edges = new vis.DataSet(relaciones);

    const data = { nodes, edges };
    const options = {
        nodes: {
            shape: 'circle',
            size: 40,
            borderWidth: 2,
            shadow: true,
            color: {
                background: '#FFD700',
                border: '#b8860b',
                highlight: { background: '#FFED4A', border: '#D97706' },
            },
            font: {
                color: '#111',
                face: 'Arial',
                size: 14,
                vadjust: 0,
                align: 'center',
            },
        },
        edges: {
            color: { color: '#888' },
            smooth: { enabled: true, type: 'dynamic' },
            width: 2,
            selectionWidth: 4,
        },
        physics: { enabled: true, stabilization: { iterations: 150 } },
        interaction: { hover: true, multiselect: true, dragView: true, zoomView: true },
    };

    const network = new vis.Network(container, data, options);

    // === Actualizar desde Livewire ===
    window.Livewire.on('actualizarMapa', (payload) => {
        nodes.clear();
        edges.clear();
        if (payload?.nodos?.length) nodes.add(payload.nodos);
        if (payload?.relaciones?.length) edges.add(payload.relaciones);
        network.fit({ animation: { duration: 300 } });
    });

    // === Selección ===
    let seleccionActual = { nodo: null, conexion: null };

    network.on('selectNode', function (params) {
        seleccionActual.nodo = params.nodes[0];
        seleccionActual.conexion = null;
        if (btnEliminarNodo) btnEliminarNodo.disabled = false;
        if (btnEliminarConexion) btnEliminarConexion.disabled = true;
    });

    network.on('selectEdge', function (params) {
        if (params.edges.length > 0) {
            const edgeId = params.edges[0];
            const edgeData = edges.get(edgeId);
            seleccionActual.nodo = null;
            seleccionActual.conexion = edgeData;
            if (btnEliminarNodo) btnEliminarNodo.disabled = true;
            if (btnEliminarConexion) btnEliminarConexion.disabled = false;
        }
    });

    network.on('deselectNode', () => {
        seleccionActual.nodo = null;
        if (btnEliminarNodo) btnEliminarNodo.disabled = true;
    });

    network.on('deselectEdge', () => {
        seleccionActual.conexion = null;
        if (btnEliminarConexion) btnEliminarConexion.disabled = true;
    });

    // === Doble clic para renombrar ===
    network.on('doubleClick', function (params) {
        if (params.nodes.length === 1) {
            const nodeId = params.nodes[0];
            const nuevoNombre = prompt("Nuevo nombre para el nodo:");
            if (nuevoNombre && nuevoNombre.trim() !== "") {
                @this.call('actualizarNodo', nodeId, nuevoNombre.trim());
            }
        }
    });

    // === Clic derecho (acciones rápidas) ===
    network.on('oncontext', function (params) {
        params.event.preventDefault();
        const nodeId = network.getNodeAt(params.pointer.DOM);
        if (!nodeId) return;

        const opcion = prompt(
            "Acción para el nodo:\n1. Cambiar color fondo\n2. Cambiar color de letra\n3. Eliminar nodo"
        );

        if (opcion === "1") {
            const nuevoColor = prompt("Nuevo color de fondo (#RRGGBB):", "#FF0000");
            if (nuevoColor && /^#([0-9A-F]{3}){1,2}$/i.test(nuevoColor.trim())) {
                @this.call('actualizarColorNodo', nodeId, nuevoColor.trim());
            }
        } else if (opcion === "2") {
            const nuevoColor = prompt("Nuevo color de letra (#RRGGBB):", "#000000");
            if (nuevoColor && /^#([0-9A-F]{3}){1,2}$/i.test(nuevoColor.trim())) {
                const nodo = nodes.get(nodeId);
                if (nodo) {
                    nodo.font.color = nuevoColor.trim();
                    @this.call('actualizarNodo', nodeId, nodo.label);
                }
            }
        } else if (opcion === "3") {
            if (confirm("¿Seguro que deseas eliminar solo este nodo y sus conexiones?")) {
                @this.call('eliminarNodoSeleccionado', nodeId);
            }
        }
    });

    // === Botones eliminar ===
    if (btnEliminarNodo) {
        btnEliminarNodo.addEventListener('click', () => {
            if (seleccionActual.nodo) {
                if (confirm("¿Eliminar nodo seleccionado y sus conexiones?")) {
                    @this.call('eliminarNodoSeleccionado', seleccionActual.nodo);
                    seleccionActual.nodo = null;
                    btnEliminarNodo.disabled = true;
                }
            } else {
                alert("Selecciona un nodo primero.");
            }
        });
    }

    if (btnEliminarConexion) {
        btnEliminarConexion.addEventListener('click', () => {
            if (seleccionActual.conexion) {
                if (confirm("¿Eliminar la conexión seleccionada?")) {
                    @this.call(
                        'eliminarRelacionSeleccionada',
                        seleccionActual.conexion.from,
                        seleccionActual.conexion.to
                    );
                    seleccionActual.conexion = null;
                    btnEliminarConexion.disabled = true;
                    network.unselectAll();
                }
            } else {
                alert("Selecciona una conexión primero.");
            }
        });
    }

    // === Fondo personalizado y opacidad ===
    if (inputFondo) {
        inputFondo.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (e) {
                const imageUrl = e.target.result;
                networkBackground.style.backgroundImage = `url('${imageUrl}')`;
                networkBackground.style.opacity = '0.4';
                Livewire.dispatch('setBackground', { base64: imageUrl });
            };
            reader.readAsDataURL(file);
        });
    }

    if (btnQuitarFondo) {
        btnQuitarFondo.addEventListener('click', () => {
            networkBackground.style.backgroundImage = 'none';
        });
    }

    if (rangoOpacidad) {
        rangoOpacidad.addEventListener('input', () => {
            const valor = rangoOpacidad.value / 100;
            networkBackground.style.opacity = valor.toString();
        });
    }

    // Redibujo forzado
    setTimeout(() => {
        network.redraw();
        network.fit({ animation: true });
    }, 400);
}

// === Ejecutar automáticamente sin recargar ===
document.addEventListener("livewire:load", renderMapa);
window.Livewire.hook('element.updated', (el, component) => {
    if (el.id && el.id.includes('network')) {
        setTimeout(renderMapa, 300);
    }
});
document.addEventListener("livewire:navigated", () => setTimeout(renderMapa, 400));
</script>


  <br>
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
  <h4>4.1.3 Nivel de Riesgo - Gráfico de Consecuencia x Factor de Ocurrencia</h4>
@if(!empty($risks))
  <br>
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
    @foreach($su as $s)
    <tr>
      <td style="border: 1px solid black;">C01</td>
      <td style="border: 1px solid black; text-align: left;">{{$s->subtitle->nombre}}</td>
      <td style="border: 1px solid black; background-color: yellow; font-weight: bold;">              
      <input type="number" min="1" max="5"  class="w-12 text-center border">
      </td>
      <td style="border: 1px solid black;">5</td>
    </tr>
    @endforeach

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
          <textarea required wire:model="que" id="que" class="w-full rounded-radius border border-outline bg-surface-alt 
          px-2.5 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary 
          disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 
          dark:focus-visible:outline-primary-dark" rows="2" placeholder="Coloca la informacion"></textarea>
      </td>
    </tr>
    <tr>
      <td style="border: 1px solid black; padding: 8px;"><b>¿Cómo?</b></td>
      <td style="border: 1px solid black; padding: 8px;">
          <textarea required wire:model="como" id="como" class="w-full rounded-radius border border-outline bg-surface-alt 
          px-2.5 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary 
          disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 
          dark:focus-visible:outline-primary-dark" rows="2" placeholder="Coloca la informacion"></textarea>
      </td>
    </tr>
    <tr>
      <td style="border: 1px solid black; padding: 8px;"><b>¿Quién?</b></td>
      <td style="border: 1px solid black; padding: 8px;">
          <textarea required wire:model="quien" id="quien" class="w-full rounded-radius border border-outline bg-surface-alt 
          px-2.5 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary 
          disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 
          dark:focus-visible:outline-primary-dark" rows="2" placeholder="Coloca la informacion"></textarea>    
      </td>
    </tr>
    <tr>
      <td style="border: 1px solid black; padding: 8px;"><b>¿Por qué?</b></td>
      <td style="border: 1px solid black; padding: 8px;">
          <textarea required wire:model="por_que" id="por_que" class="w-full rounded-radius border border-outline bg-surface-alt 
          px-2.5 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary 
          disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 
          dark:focus-visible:outline-primary-dark" rows="2" placeholder="Coloca la informacion"></textarea>    
      </td>
    </tr>
    <tr>
      <td style="border: 1px solid black; padding: 8px;"><b>¿Dónde?</b></td>
      <td style="border: 1px solid black; padding: 8px;">
          <textarea required wire:model="donde" id="donde" class="w-full rounded-radius border border-outline bg-surface-alt 
          px-2.5 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary 
          disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 
          dark:focus-visible:outline-primary-dark" rows="2" placeholder="Coloca la informacion"></textarea>
      </td>
    </tr>
    <tr>
      <td style="border: 1px solid black; padding: 8px;"><b>¿Cuánto?</b></td>
      <td style="border: 1px solid black; padding: 8px;">
          <textarea required wire:model="cuanto" id="cuanto" class="w-full rounded-radius border border-outline bg-surface-alt 
          px-2.5 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary 
          disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 
          dark:focus-visible:outline-primary-dark" rows="2" placeholder="Coloca la informacion"></textarea>
      </td>
    </tr>
    <tr>
      <td style="border: 1px solid black; padding: 8px;"><b>¿Cuándo?</b></td>
      <td style="border: 1px solid black; padding: 8px;">
        <div class="flex w-full max-w-2xl gap-4 text-on-surface dark:text-on-surface-dark">
          De
          <div>
              <input required wire:model="de" id="de" type="date" name="de" class="bg-white w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 
              text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary 
              disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
              style="border-color:rgba(31, 89, 177, 1);" />
          </div>
          Hasta
          <div>
              <input required wire:model="hasta" id="hasta" type="date" name="hasta" class="bg-white w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 
              text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary 
              disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
              style="border-color:rgba(31, 89, 177, 1);" />
          </div>
        </div>  
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