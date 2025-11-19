<div>
    <!-- Bloque Quill -->
<div 
    x-data 
    wire:ignore
    x-init="
        const init = async () => {
            if (typeof Quill === 'undefined') return setTimeout(init, 150);

            // 🟢 Inicializa el editor
            const quill = new Quill($refs.editorTit, {
                theme: 'snow',
                modules: {
                    toolbar: {
                        container: [
                            [{ header: [1, 2, false] }],
                            ['bold', 'italic', 'underline'],
                            [{ align: [] }],
                            [{ list: 'ordered' }, { list: 'bullet' }],
                            [{ script: 'sub' }, { script: 'super' }],
                            ['clean']
                        ]
                    }
                }
            });

            quill.root.innerHTML = @js($contenido ?? '');

            const reporteId = {{ $rp }};
            const contenidoId = {{ $contentId ?? 0 }};
            window.referenciasActuales = @js($referencias ?? []);

            const toolbar = quill.getModule('toolbar').container;
            const refGroup = document.createElement('span');
            refGroup.classList.add('ql-formats');

            /* ===========================
               🔖 AGREGAR REFERENCIA
            ============================ */
            const addRefBtn = document.createElement('button');
            addRefBtn.type = 'button';
            addRefBtn.title = 'Agregar referencia';
            addRefBtn.innerHTML = '🔖';
            addRefBtn.onclick = async (e) => {
                e.preventDefault();

                const texto = prompt('Introduce la referencia o URL:');
                if (!texto) return;

                // 🚀 Nuevo número de referencia (intermedia si aplica)
                const num = await $wire.call('insertarReferenciaIntermedia', reporteId, contenidoId, texto);

                // Insertar visualmente
                const refHtml = `<sup style='color:#0f4a75; cursor:pointer;'>[${num}]</sup>`;
                const range = quill.getSelection(true);
                quill.clipboard.dangerouslyPasteHTML(range.index, refHtml);

                // Agregar al array local
                window.referenciasActuales.push({ num, texto });
                await $wire.set('referencias', window.referenciasActuales);

                alert(`Referencia [${num}] agregada ✅`);
            };
            refGroup.appendChild(addRefBtn);

            /* ===========================
               ✏️ EDITAR REFERENCIA
            ============================ */
            const editBtn = document.createElement('button');
            editBtn.type = 'button';
            editBtn.title = 'Editar referencia';
            editBtn.innerHTML = '✏️';
            editBtn.onclick = () => {
                if (window.referenciasActuales.length === 0) return alert('No hay referencias para editar.');
                abrirModal('editar');
            };
            refGroup.appendChild(editBtn);

            /* ===========================
               🗑️ ELIMINAR REFERENCIA
            ============================ */
            const deleteBtn = document.createElement('button');
            deleteBtn.type = 'button';
            deleteBtn.title = 'Eliminar referencia';
            deleteBtn.innerHTML = '🗑️';
            deleteBtn.onclick = () => {
                if (window.referenciasActuales.length === 0) return alert('No hay referencias para eliminar.');
                abrirModal('eliminar');
            };
            refGroup.appendChild(deleteBtn);

            toolbar.appendChild(refGroup);

            /* ===========================
               🪟 MODAL FLOTANTE
            ============================ */
            const modal = document.createElement('div');
            modal.id = 'refModal';
            modal.classList.add('hidden');
            Object.assign(modal.style, {
                position: 'fixed',
                top: '50%',
                left: '50%',
                transform: 'translate(-50%, -50%)',
                zIndex: '9999',
                background: 'white',
                border: '2px solid #0f4a75',
                borderRadius: '10px',
                padding: '20px',
                boxShadow: '0 6px 20px rgba(0,0,0,0.25)',
                width: '340px',
                textAlign: 'center',
                fontFamily: 'Arial, sans-serif'
            });
            document.body.appendChild(modal);

            const abrirModal = (accion) => {
                modal.innerHTML = '';
                modal.classList.remove('hidden');

                const title = document.createElement('h2');
                title.textContent = accion === 'editar' ? 'Editar referencia' : 'Eliminar referencia';
                title.style.fontWeight = 'bold';
                title.style.fontSize = '17px';
                title.style.color = '#0f4a75';
                modal.appendChild(title);

                const select = document.createElement('select');
                select.id = 'refSelect';
                select.style.cssText = 'width:100%; margin-top:10px; padding:6px; border:1px solid #ccc; border-radius:5px;';
                select.innerHTML = window.referenciasActuales
                    .map(r => `<option value='${r.num}'>[${r.num}] ${r.texto}</option>`)
                    .join('');
                modal.appendChild(select);

                if (accion === 'editar') {
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.id = 'refEditText';
                    input.placeholder = 'Nuevo texto';
                    input.style.cssText = 'width:100%; margin-top:10px; padding:6px; border:1px solid #ccc; border-radius:5px;';
                    modal.appendChild(input);
                }

                const btnDiv = document.createElement('div');
                btnDiv.style.marginTop = '15px';
                btnDiv.style.display = 'flex';
                btnDiv.style.justifyContent = 'center';
                btnDiv.style.gap = '10px';

                const saveBtn = document.createElement('button');
                saveBtn.type = 'button';
                saveBtn.textContent = accion === 'editar' ? 'Guardar' : 'Eliminar';
                saveBtn.style.cssText = accion === 'editar'
                    ? 'background:#0f4a75; color:white; padding:5px 10px; border-radius:5px;'
                    : 'background:#c0392b; color:white; padding:5px 10px; border-radius:5px;';

                const cancelBtn = document.createElement('button');
                cancelBtn.type = 'button';
                cancelBtn.textContent = 'Cancelar';
                cancelBtn.style.cssText = 'background:#ccc; padding:5px 10px; border-radius:5px;';

                btnDiv.appendChild(saveBtn);
                btnDiv.appendChild(cancelBtn);
                modal.appendChild(btnDiv);

                cancelBtn.onclick = () => modal.classList.add('hidden');

                saveBtn.onclick = async () => {
                    const num = select.value;

                    if (accion === 'editar') {
                        const nuevoTexto = modal.querySelector('#refEditText').value.trim();
                        if (!nuevoTexto) return alert('Debes escribir un texto nuevo.');
                        const refObj = window.referenciasActuales.find(r => r.num == num);
                        if (refObj) {
                            // 🔹 Actualiza en base de datos
                            const ok = await $wire.call('actualizarReferencia', reporteId, parseInt(num,10), nuevoTexto);
                            if (ok) {
                                refObj.texto = nuevoTexto;
                                await $wire.set('referencias', window.referenciasActuales);
                                alert('Referencia actualizada correctamente ✅');
                            } else {
                                alert('⚠️ No se pudo actualizar la referencia.');
                            }
                        }
                        modal.classList.add('hidden');
                        return;
                    }

                    // 🧹 ELIMINAR + RENOMBRAR GLOBAL
                    const result = await $wire.call('eliminarYRenumerarReferencia', reporteId, parseInt(num,10));
                    const map = result.map || {};
                    const nuevasRefs = result.referencias || [];

                    // Actualiza los sup dentro del editor actual
                    Array.from(quill.root.querySelectorAll('sup')).forEach((sup) => {
                        const match = sup.innerText.match(/\[(\d+)\]/);
                        if (!match) return;
                        const oldNum = parseInt(match[1], 10);
                        if (!(oldNum in map)) return;
                        const newNum = map[oldNum];
                        if (newNum === null) {
                            sup.remove();
                        } else {
                            sup.innerText = `[${newNum}]`;
                        }
                    });

                    // Actualiza Livewire
                    window.referenciasActuales = nuevasRefs;
                    await $wire.set('referencias', window.referenciasActuales);

                    alert('Referencia eliminada y renumerada globalmente ✅');
                    modal.classList.add('hidden');
                };
            };

            // 🔄 Sincroniza contenido con Livewire
            quill.on('text-change', () => {
                const html = quill.root.innerHTML;
                $refs.textareaTit.value = html;
                $refs.textareaTit.dispatchEvent(new Event('input'));
            });
        };
        init();
    "
>
    <div x-ref="editorTit" style="height:220px; background:white;"></div>
    <textarea x-ref="textareaTit" wire:model="contenido" class="hidden"></textarea>
</div>



  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
  <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>


<div class="flex gap-6 flex-wrap">

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
</div>

        <!-- Imagen 2 -->
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
    </div>
        <!-- Imagen 3 -->
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
  @if($titulo==32)
      <style>
        /* 🔹 Encabezados verticales */
        .vertical {
            writing-mode: vertical-rl;
            transform: rotate(360deg);
            white-space: normal;      /* Permite saltos de línea */
            word-break: break-word;   /* Corta palabras largas si es necesario */
            text-align: center;
            vertical-align: middle;
            height: 140px;            /* Ajusta según tu diseño */
            width: 30px;              /* 🔹 Reduce el ancho para forzar salto de línea */
            padding: 0 4px;
        }
    </style>
<div>
    @if (session('success'))
        <div class="bg-green-200 text-green-800 p-2 rounded mb-3">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full border-collapse text-center text-sm font-sans" style="border:1px solid black;">
        <thead>
            <tr style="background-color:#002060; color:white;">
                <th rowspan="2" style="border:2px solid #ffffffff;" class="border p-2">No.</th>
                <th rowspan="2" style="border:2px solid #ffffffff;" class="border p-2">Tipo de Riesgo</th>
                <th colspan="5" style="border:2px solid #ffffffff;" class="border p-2">Criterios de Evaluación</th>
                <th rowspan="2" style="border:2px solid #ffffffff;" class="border p-2">Total<br>Posible</th>
                <th rowspan="2" style="border:2px solid #ffffffff;" class="border p-2">Cal.</th>
                <th rowspan="2" style="border:2px solid #ffffffff;" class="border p-2">Clase de Riesgo</th>
                <th rowspan="2" style="border:2px solid #ffffffff;" class="border p-2">Factor de ocurrencia<br>del riesgo</th>
            </tr>
            <tr style="background-color:#002060; color:white;">
                <th style="border:2px solid #ffffffff;" class="vertical">Impacto en las Funciones</th>
                <th style="border:2px solid #ffffffff;" class="vertical">Impacto en la Organización</th>
                <th style="border:2px solid #ffffffff;" class="vertical">Extensión del Daño</th>
                <th style="border:2px solid #ffffffff;" class="vertical">Probabilidad de Materialización</th>
                <th style="border:2px solid #ffffffff;" class="vertical">Impacto Financiero</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($riesgos as $i => $r)
                <tr>
                    <td class="border p-1" style="border:1px solid #001a4d;">{{ $r['no'] }}</td>
                    <td class="border p-1 text-left" style="border:1px solid #001a4d;">{{ $r['riesgo'] }}</td>

                    @foreach (['impacto_f','impacto_o','extension_d','probabilidad_m','impacto_fin'] as $campo)
                        <td class="border p-1 font-bold" style="border:1px solid #001a4d;">
                          <input 
                              type="number"
                              min="1"
                              max="5"
                              step="1"
                              
                              oninput="this.value = Math.max(1, Math.min(5, this.value))"
                              wire:model.lazy="riesgos.{{ $i }}.{{ $campo }}"
                              wire:blur="recalcularRiesgosFila({{ $i }})"                              
                              class="w-14 text-center border-gray-400 rounded"
                          />
                        </td>
                    @endforeach

                    <td class="border p-1" style="border:1px solid #001a4d;">25</td>
                    <td class="border p-1 font-semibold" style="border:1px solid #001a4d;">{{ $r['cal'] ?? '' }}</td>
                    <td class="border p-1 font-bold text-black"
                        style=" border:1px solid #001a4d;
                        background-color:
                            {{ ($r['clase_riesgo'] ?? '') == 'MUY ALTO' ? '#ff0000' :
                               (($r['clase_riesgo'] ?? '') == 'ALTO' ? '#ff6600' :
                               (($r['clase_riesgo'] ?? '') == 'MEDIO' ? '#ffc000' :
                               (($r['clase_riesgo'] ?? '') == 'BAJO' ? '#75d5ecff' : 'transparent'))) }}">
                        {{ $r['clase_riesgo'] ?? '' }}
                    </td>
                    <td class="border p-1" style="border:1px solid #001a4d;">{{ number_format((float) ($r['factor_oc'] ?? 0), 2) . '%' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
    <br>
<div class="justify-center items-center place-items-center">
<div class="relative flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark items-center">
    <label for="chartType" class="w-fit pl-0.5 text-xl text-black" >Tipo de gráfico:</label>
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

let chartInstance = null; // 👈 fuera de la función
  function inicializarGrafica() {
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
            if (v >= 84) return "#ff0000";
            if (v >= 64) return "#ff6600";
            if (v >= 44) return "#ffc000";
            if (v >= 1) return "#75d5ecff";
            return "rgba(102, 209, 98, 0.9)";
        });

        const esCircular = ['pie', 'doughnut', 'polarArea'].includes(tipo);
        if (esCircular) {
            canvas.width = 700;
            canvas.height = 700;
        } else {
            canvas.width = 900;
            canvas.height = 450;
        }
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
                layout: { padding: { top: 20 } },
                responsive: false,
                maintainAspectRatio: false,
                animation: false,
                plugins: {
                title: {
                    display: true,           // 🔹 Activa el título
                    text: 'Gráfica de exposición general', // 🔹 Texto del título
                    color: '#000000ff',        // 🔹 Color del texto
                    font: {
                    size: 14,              // 🔹 Tamaño del texto       
                    family: 'Segoe UI'     // 🔹 Fuente
                    },
                    padding: {
                    top: 2,               // 🔹 Espacio arriba
                    bottom: 20             // 🔹 Espacio entre el título y la gráfica
                    }
                },
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
                    y: { beginAtZero: true, max: 100, ticks: { color: '#000' } },
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

const dataInicial = @json($riesgos ?? []);
const tipoInicial = @json($grafica ?? 'bar');
select.value = tipoInicial; // 🔹 Esto asegura que el select muestre la opción guardada
crearGrafico(dataInicial, tipoInicial);
}

</script>
@endpush
</div>
<div class="p-4">
    <div class="mb-6 flex items-center justify-center p-4 rounded-lg" style="background-color: rgba(113, 150, 206, 1);">
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
                   class="w-16 border rounded px-1 py-0.5 text-center text-sm"style="border-color:rgba(31, 89, 177, 1);">
        </label>

        <button type="button"
                wire:click="agregarNodoDesdeFront"
                class="bg-sky-700 text-white px-3 py-1 rounded hover:bg-sky-800">
            Agregar Nodo
        </button>
    </div>

    {{-- Conectar nodos --}}
    <div class="flex flex-wrap justify-center gap-3 mb-3">
        <select wire:model="nodoDesde" class="border px-2 py-1 rounded text-sm"style="border-color:rgba(31, 89, 177, 1);">
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
                <input type="file"
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
<script>
function renderMapa() {
    const container = document.getElementById('network');
    const networkBackground = document.getElementById('network-bg');
    const inputFondo = document.getElementById('inputFondo');
    const btnQuitarFondo = document.getElementById('btnQuitarFondo');
    const rangoOpacidad = document.getElementById('rangoOpacidad');
    const btnEliminarNodo = document.getElementById('btnEliminarNodo');
    const btnEliminarConexion = document.getElementById('btnEliminarConexion');

    if (!container) {
        console.warn("⚠️ No se encontró el contenedor #network.");
        return;
    }
    if (typeof vis === 'undefined') {
        console.error("❌ Vis.js no está disponible todavía.");
        return;
    }

    // 🚫 Evita redibujos múltiples
    if (window.__renderingMapa) return;
    window.__renderingMapa = true;
    setTimeout(() => (window.__renderingMapa = false), 500);

    // === Datos desde backend ===
    const nodos = @json($nodos ?? []);
    const relaciones = @json($relaciones ?? []);
    const fondo = @json($background_image ?? null);
    const opacidad = @json($background_opacity ?? 0.4);

    // === Inicialización de nodos y relaciones ===
    const nodes = new vis.DataSet(nodos);
    const edges = new vis.DataSet(relaciones);
    const data = { nodes, edges };

    const options = {
        nodes: {
            shape: "circle",
            size: 40,
            borderWidth: 2,
            shadow: true,
            color: {
                background: "#FFD700",
                border: "#b8860b",
                highlight: { background: "#FFED4A", border: "#D97706" },
            },
            font: {
                color: "#111",
                face: "Arial",
                size: 14,
                vadjust: 0,
                align: "center",
            },
        },
        edges: {
            color: { color: "#888" },
            smooth: { enabled: true, type: "dynamic" },
            width: 2,
            selectionWidth: 4,
        },
        physics: { enabled: true, stabilization: { iterations: 150 } },
        interaction: { hover: true, multiselect: true, dragView: true, zoomView: true, navigationButtons: true },
    };

    // === Fondo inicial ===
    if (fondo && networkBackground) {
        networkBackground.style.backgroundImage = `url('${fondo}')`;
        networkBackground.style.opacity = opacidad;
    }

    // === Crear red ===
    const network = new vis.Network(container, data, options);

    // === Actualizar desde Livewire ===
    window.Livewire.on("actualizarMapa", (payload) => {
        nodes.clear();
        edges.clear();
        if (payload?.nodos?.length) nodes.add(payload.nodos);
        if (payload?.relaciones?.length) edges.add(payload.relaciones);
        network.fit({ animation: { duration: 300 } });
    });

    // === Selección ===
    let seleccionActual = { nodo: null, conexion: null };

    network.on("selectNode", (params) => {
        seleccionActual.nodo = params.nodes[0];
        seleccionActual.conexion = null;
        if (btnEliminarNodo) btnEliminarNodo.disabled = false;
        if (btnEliminarConexion) btnEliminarConexion.disabled = true;
    });

    network.on("selectEdge", (params) => {
        if (params.edges.length > 0) {
            const edgeId = params.edges[0];
            const edgeData = edges.get(edgeId);
            seleccionActual.nodo = null;
            seleccionActual.conexion = edgeData;
            if (btnEliminarNodo) btnEliminarNodo.disabled = true;
            if (btnEliminarConexion) btnEliminarConexion.disabled = false;
        }
    });

    network.on("deselectNode", () => {
        seleccionActual.nodo = null;
        if (btnEliminarNodo) btnEliminarNodo.disabled = true;
    });

    network.on("deselectEdge", () => {
        seleccionActual.conexion = null;
        if (btnEliminarConexion) btnEliminarConexion.disabled = true;
    });

    // === Doble clic: renombrar nodo ===
    network.on("doubleClick", (params) => {
        if (params.nodes.length === 1) {
            const nodeId = params.nodes[0];
            const nuevoNombre = prompt("Nuevo nombre para el nodo:");
            if (nuevoNombre && nuevoNombre.trim() !== "") {
                @this.call("actualizarNodo", nodeId, nuevoNombre.trim());
            }
        }
    });

    // === Clic derecho: acciones rápidas ===
    network.on("oncontext", (params) => {
        params.event.preventDefault();
        const nodeId = network.getNodeAt(params.pointer.DOM);
        if (!nodeId) return;

        const opcion = prompt(
            "Acción para el nodo:\n1. Cambiar color fondo\n2. Cambiar color de letra\n3. Eliminar nodo"
        );

        if (opcion === "1") {
            const nuevoColor = prompt("Nuevo color de fondo (#RRGGBB):", "#FF0000");
            if (nuevoColor && /^#([0-9A-F]{3}){1,2}$/i.test(nuevoColor.trim())) {
                @this.call("actualizarColorNodo", nodeId, nuevoColor.trim());
            }
        } else if (opcion === "2") {
            const nuevoColor = prompt("Nuevo color de letra (#RRGGBB):", "#000000");
            if (nuevoColor && /^#([0-9A-F]{3}){1,2}$/i.test(nuevoColor.trim())) {
                const nodo = nodes.get(nodeId);
                if (nodo) {
                    nodo.font.color = nuevoColor.trim();
                    @this.call("actualizarNodo", nodeId, nodo.label);
                }
            }
        } else if (opcion === "3") {
            if (confirm("¿Seguro que deseas eliminar solo este nodo y sus conexiones?")) {
                @this.call("eliminarNodoSeleccionado", nodeId);
            }
        }
    });

    // === Botones eliminar ===
    if (btnEliminarNodo) {
        btnEliminarNodo.addEventListener("click", () => {
            if (seleccionActual.nodo) {
                if (confirm("¿Eliminar nodo seleccionado y sus conexiones?")) {
                    @this.call("eliminarNodoSeleccionado", seleccionActual.nodo);
                    seleccionActual.nodo = null;
                    btnEliminarNodo.disabled = true;
                }
            } else {
                alert("Selecciona un nodo primero.");
            }
        });
    }

    if (btnEliminarConexion) {
        btnEliminarConexion.addEventListener("click", () => {
            if (seleccionActual.conexion) {
                if (confirm("¿Eliminar la conexión seleccionada?")) {
                    @this.call(
                        "eliminarRelacionSeleccionada",
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

    // === Fondo personalizado (crear/editar) ===
    if (inputFondo) {
        inputFondo.addEventListener("change", (event) => {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                const imageUrl = e.target.result;
                networkBackground.style.backgroundImage = `url('${imageUrl}')`;
                networkBackground.style.opacity = "0.4";

                if (window.Livewire) {
                    const comps = Object.values(window.Livewire.components.componentsById || {});
                    const comp = comps[0];
                    if (comp) {
                        comp.$wire.call('setBackground', imageUrl); // ✅ variable correcta
                        console.log("✅ Fondo enviado correctamente (base64 detectado)");
                    } else {
                        console.warn("⚠️ No se encontró componente Livewire activo.");
                    }
                }
            };
            reader.readAsDataURL(file);
        });
    }

    if (btnQuitarFondo) {
        btnQuitarFondo.addEventListener("click", () => {
            networkBackground.style.backgroundImage = "none";
            if (window.Livewire) {
                const comps = Object.values(window.Livewire.components.componentsById || {});
                const comp = comps[0];
                if (comp) {
                    comp.$wire.call('setBackground', '');
                    console.log("🧹 Fondo eliminado del componente Livewire.");
                }
            }
        });
    }

    // === Opacidad dinámica ===
    if (rangoOpacidad) {
        rangoOpacidad.addEventListener("input", () => {
            const valor = rangoOpacidad.value / 100;
            networkBackground.style.opacity = valor.toString();
        });
    }

    // === Redibujo ===
    setTimeout(() => {
        network.redraw();
        network.fit({ animation: true });
    }, 400);
}

// === Hooks de Livewire ===
document.addEventListener("livewire:load", renderMapa);
window.Livewire.hook("element.updated", (el, component) => {
    if (el.id && el.id.includes("network")) {
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

   @if ($titulo==33)
<div class="relative flex flex-col items-center justify-center min-h-screen bg-white p-10 font-sans select-none">
    <h1 class="text-3xl font-bold mb-8 text-[#002060] uppercase tracking-wide">
        Análisis FODA
    </h1>

    <!-- 🟠 Círculo principal -->
    <div class="relative w-[750px] h-[750px] rounded-full overflow-visible shadow-xl border-8 border-white">

        <!-- 🟧 Fortalezas -->
        <div class="absolute top-0 left-0 w-1/2 h-1/2 bg-[#F47B20] text-white 
                    flex flex-col items-center justify-start text-center pt-10 rounded-tl-full border-r-8 border-b-8 border-white">
              <h2 class="absolute text-xl font-bold tracking-wide"
                  style="top: 250px; left: 160px;">
                  FORTALEZAS
              </h2>  
        </div>

        <!-- ⚫ Debilidades -->
        <div class="absolute top-0 right-0 w-1/2 h-1/2 bg-[#808285] text-white 
                    flex flex-col items-center justify-start text-center pt-10 rounded-tr-full border-l-8 border-b-8 border-white">
              <h2 class="absolute text-xl font-bold tracking-wide"
                  style="top: 250px; left: 20px;">
                  DEBILIDADES
              </h2>
        </div>

        <!-- 🟦 Oportunidades -->
        <div class="absolute bottom-0 left-0 w-1/2 h-1/2 bg-[#0072BC] text-white 
                    flex flex-col items-center justify-end text-center pb-10 rounded-bl-full border-r-8 border-t-8 border-white">
              <h2 class="absolute text-xl font-bold tracking-wide"
                  style="top: 80px; left: 150px;">
                  OPORTUNIDADES
              </h2>
        </div>

        <!-- 🟨 Amenazas -->
        <div class="absolute bottom-0 right-0 w-1/2 h-1/2 bg-[#FDB913] text-white 
                    flex flex-col items-center justify-end text-center pb-10 rounded-br-full border-l-8 border-t-8 border-white">
              <h2 class="absolute text-xl font-bold tracking-wide"
                  style="top: 80px; left: 20px;">
                  AMENAZAS
              </h2>
        </div>

        <!-- 🔁 Flechas centrales -->
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-20">
            <div class="w-16 h-16 rounded-full bg-white shadow-md flex items-center justify-center">
                  <img src="{{ asset('contenido/ciclo.png') }}" >
            </div>
        </div>

        <!-- 📦 Cuadro Fortalezas -->
        <div class="absolute top-[40px] left-[-100px] z-30 w-[260px] h-[130px] 
                    border-2 border-[#F47B20] bg-white/95 rounded-xl p-3 shadow-md flex flex-col">
            <textarea wire:model.defer="fortalezas"
                      class="w-full h-full p-2 text-sm text-[#002060] resize-none focus:outline-none bg-transparent"
                      placeholder="• Escribe las fortalezas aquí..."></textarea>
        </div>

        <!-- 📦 Cuadro Debilidades -->
        <div class="absolute top-[40px] right-[-100px] z-30 w-[260px] h-[130px] 
                    border-2 border-[#808285] bg-white/95 rounded-xl p-3 shadow-md flex flex-col">
            <textarea wire:model.defer="debilidades"
                      class="w-full h-full p-2 text-sm text-[#002060] resize-none focus:outline-none bg-transparent"
                      placeholder="• Escribe las debilidades aquí..."></textarea>
        </div>

        <!-- 📦 Cuadro Oportunidades -->
        <div class="absolute bottom-[40px] left-[-100px] z-30 w-[260px] h-[130px] 
                    border-2 border-[#0072BC] bg-white/95 rounded-xl p-3 shadow-md flex flex-col">
            <textarea wire:model.defer="oportunidades"
                      class="w-full h-full p-2 text-sm text-[#002060] resize-none focus:outline-none bg-transparent"
                      placeholder="• Escribe las oportunidades aquí..."></textarea>
        </div>

        <!-- 📦 Cuadro Amenazas -->
        <div class="absolute bottom-[40px] right-[-100px] z-30 w-[260px] h-[130px] 
                    border-2 border-[#FDB913] bg-white/95 rounded-xl p-3 shadow-md flex flex-col">
            <textarea wire:model.defer="amenazas"
                      class="w-full h-full p-2 text-sm text-[#002060] resize-none focus:outline-none bg-transparent"
                      placeholder="• Escribe las amenazas aquí..."></textarea>
        </div>
    </div>
</div>

  @endif
  
  @if ($titulo==16)
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

<br>

<!-- Contenedor centrado -->
<div class="w-full flex justify-center">
    <div id="chart-container" class="mx-auto transition-all duration-300" style="width: 900px; height: 450px;">
        <canvas id="riesgosChart"></canvas>
    </div>
</div>

@push('scripts')
<script>
function renderRiesgosChart() {
    const canvas = document.getElementById('riesgosChart');
    const select = document.getElementById('chartType');
    const container = document.getElementById('chart-container');
    if (!canvas || !select || !container) return;

    const ctx = canvas.getContext('2d');

    const riesgos = @json($risks->sortBy('no')->map(fn($r) => $r->no . ' - ' . $r->riesgo)->values());
    const riesg = @json($risks->sortBy('no')->map(fn($r) => $r->no)->values());
    const ocurrencias = @json($risks->sortBy('no')->pluck('factor_oc')->values());
    // 🧩 Convertir "25%" → 25 (número)
    const ocurrenciasNum = ocurrencias.map(v => parseFloat(String(v).replace('%', '')) || 0);
    const tipoInicial = @json($grafica);

    const colores = ocurrencias.map(v => {
        if (v >= 80) return "rgba(206, 0, 0, 0.9)";
        if (v >= 60) return "rgba(235, 231, 0, 0.9)";
        if (v >= 40) return "rgba(4, 121, 0, 0.9)";
        return "rgba(102, 209, 98, 0.9)";
    });

    // 🔹 Ajuste dinámico del tamaño según el tipo
    function ajustarTamañoCanvas(tipo) {
        let width = 1000;
        let height = 500;

        switch (tipo) {
            case 'pie':
            case 'doughnut':
            case 'polarArea':
                width = 400;
                height = 400;
                break;
            case 'bar':
                width = 1000;
                height = 500;
                break;
        }

        container.style.width = width + 'px';
        container.style.height = height + 'px';
    }

    function crearGrafico(tipo) {
        if (window.riesgosChartInstance) window.riesgosChartInstance.destroy();

        ajustarTamañoCanvas(tipo);

        const esCircular = ['pie', 'doughnut', 'polarArea'].includes(tipo);

        const dataConfig = esCircular
            ? {
                labels: riesgos,
                labe: riesg,
                datasets: [{
                    label: 'Factor de ocurrencia',
                    data: ocurrenciasNum,
                    backgroundColor: colores
                }]
            }
            : {
                labels: ['Factor de ocurrencia'],
                datasets: riesgos.map((nombre, i) => ({
                    label: nombre,
                    data: [ocurrenciasNum[i]],
                    backgroundColor: colores[i],
                    numero: riesg[i],
                }))
            };

        Chart.register(ChartDataLabels);

        window.riesgosChartInstance = new Chart(ctx, {
            type: tipo,
            data: dataConfig,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
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
                                const dataset = ctx.chart.data.datasets[ctx.datasetIndex];
                                return `${dataset.numero} (${value})`;
                            }
                        }
                    }
                },
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

    // 🧠 Gráfico inicial
    select.value = tipoInicial ?? 'bar';
    crearGrafico(tipoInicial ?? 'bar');

    // 🧩 Cambios manuales
    select.addEventListener('change', (e) => crearGrafico(e.target.value));

    // 🔁 Cambios Livewire (wire:model)
    document.addEventListener('livewire:update', () => {
        const nuevoTipo = @this.grafica;
        select.value = nuevoTipo;
        crearGrafico(nuevoTipo);
    });
}

// ⚙️ Redibujar en todas las situaciones
document.addEventListener('DOMContentLoaded', renderRiesgosChart);
document.addEventListener('livewire:navigated', () => setTimeout(renderRiesgosChart, 100));
if (window.Livewire) {
    Livewire.hook('morph.updated', () => setTimeout(renderRiesgosChart, 100));
}
</script>
@endpush

  <div>
    <table id="tabla" style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 10pt; text-align: left;">
        <tr class="border border-black" style="background-color: #02085bff; font-weight: bold; text-align: left;">
            <td colspan="3" class="p-3" style="color: white;">  Características del Riesgo.</td>
        </tr>
        <!-- Pendientes -->
      <tr class="border border-black" style="background-color: #ffc107; font-weight: bold; text-align: center;">
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

 <table class="w-full border-collapse text-center text-sm font-sans" style="border:1px solid black;">
    <thead>
      <tr style="background-color:#002060; color:white;">
        <th class="border p-4 w-1/4">ACCIONES DIVERSAS</th>
        <th class="border p-4 w-3/4">TRATAMIENTO GENERAL DE LOS RIESGOS IDENTIFICADOS</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="border p-2 bg-[#10284A] text-white align-top">
          Medidas preventivas actuales
        </td>
        <td class="border p-2 align-top">
            <div 
                x-data 
                wire:ignore
                x-init="
                    const init = () => {
                        if (typeof Quill === 'undefined') { 
                            return setTimeout(init, 150); 
                        }

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

                        quill.root.innerHTML = @js($contenido ?? '');

                        quill.on('text-change', () => {
                            const html = quill.root.innerHTML;
                            $refs.textareaTit.value = html;
                            $refs.textareaTit.dispatchEvent(new Event('input'));
                        });
                    };
                    init();
                "
            >
        <div x-ref="editorTit" style="height:200px; background:white;"></div>
        <textarea x-ref="textareaTit" wire:model="contenido_m_p_a" class="hidden"></textarea>
    </div>
    </td>
      </tr>
      <tr>
        <td class="border p-2 bg-[#10284A] text-white align-top">
          Acciones / Planes por realizar
        </td>
        <td class="border p-2 align-top">
          <div 
            x-data 
            wire:ignore
            x-init="
                const init = () => {
                    if (typeof Quill === 'undefined') { 
                        return setTimeout(init, 150); 
                    }

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

                    quill.root.innerHTML = @js($contenido ?? '');

                    quill.on('text-change', () => {
                        const html = quill.root.innerHTML;
                        $refs.textareaTit.value = html;
                        $refs.textareaTit.dispatchEvent(new Event('input'));
                    });
                };
                init();
            "
        >
            <div x-ref="editorTit" style="height:200px; background:white;"></div>
            <textarea x-ref="textareaTit" wire:model="contenido_a_p" class="hidden"></textarea>
        </div>
        </td>
      </tr>
    </tbody>
  </table>

  <br>

  @endif
  @if ($titulo==18)
  <table class="w-full border-collapse text-center text-sm font-sans" 
          style="border:1px solid #ffffffff; border-collapse:collapse;">
      <thead>
        <tr class="bg-[#002060] font-bold text-center border border-dotted border-white">
            <td colspan="5" class="border border-dotted border-white px-1 p-4 font-bold text-white">ORGANIGRAMA DE CONTROLES GENERALES DE ACTUACIÓN</td>
        </tr>
        <tr style="background-color:#002060; color:white; border:1px solid #001a4d;">
          <th style="border:1px solid #ffffffff; padding:8px; width:5%;">No.</th>
          <th style="border:1px solid #ffffffff; padding:8px; width:15%;">Tipo de Riesgo</th>
          <th style="border:1px solid #ffffffff; padding:8px; width:25%;">Medidas preventivas actuales</th>
          <th style="border:1px solid #ffffffff; padding:8px; width:40%;">Acciones / Planes por realizar</th>
          <th style="border:1px solid #ffffffff; padding:8px; width:15%;">Estatus</th>
        </tr>
      </thead>
            <tbody>
  @foreach ($riesgs as $i => $r)
  <tr>
    <td class="border p-1" style="border:1px solid #000000ff;">{{ $r['no'] }}</td>
    <td class="border p-1 text-left" style="border:1px solid #000000ff;">{{ $r['riesgo'] }}</td>

    <td style="border:1px solid #000000ff; padding:6px;">
      <textarea
        wire:model.defer="riesgs.{{ $i }}.medidas_p"
        class="w-full border border-outline bg-surface-alt px-2.5 py-2 text-sm focus:outline-primary"
        placeholder="Coloca la información"></textarea>
    </td>

    <td style="border:1px solid #000000ff; padding:6px;">
      <textarea
        wire:model.defer="riesgs.{{ $i }}.acciones_planes"
        class="w-full border border-outline bg-surface-alt px-2.5 py-2 text-sm focus:outline-primary"
        placeholder="Coloca la información"></textarea>
    </td>

    <td style="border:1px solid #000000ff; padding:6px;"></td>
  </tr>
  @endforeach
</tbody>

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
    @if($titulo==38)
        <!-- 📋 RECOMENDACIONES Y ACCIONES DE SEGURIDAD FÍSICA -->
        <div class="border w-full font-sans">
        <!-- Encabezado principal -->
        <table class="w-full border-collapse text-center text-xm border border-dotted border-white text-[16px]">
            <thead>
                <tr class="bg-[#002060] text-white font-bold text-sm border border-dotted border-white text-[16px]">
                    <th colspan="9" class="border border-dotted border-white py-2" style="border:2px #ffffffff;">RECOMENDACIONES Y ACCIONES DE SEGURIDAD FÍSICA</th>
                </tr>
                <tr class="text-white font-bold">
                    <th class="bg-[#002060] border border-dotted border-white px-2 py-1">ALTO<br>/<br>URGENTE<br>LO ANTES POSIBLE</th>
                    <th class="bg-[#C00000] border border-dotted border-white px-2 py-1">URGENTE</th>
                    <th class="bg-[#002060] border border-dotted border-white px-2 py-1"> </th>
                    <th class="bg-[#002060] border border-dotted border-white px-2 py-1">MEDIANO<br>/<br>IMPORTANTE<br>EN EL CORTO TIEMPO</th>
                    <th class="bg-[#FFFF00] text-black border border-dotted border-white px-2 py-1">MEDIO</th>
                    <th class="bg-[#002060] border border-dotted border-white px-2 py-1"> </th>
                    <th class="bg-[#002060] border border-dotted border-white px-2 py-1">BAJO<br>/<br>OBLIGATORIA<br>REALIZAR A MEDIANO PLAZO</th>
                    <th class="bg-[#00B0F0] border border-dotted border-white px-2 py-1">BAJO</th>
                    <th class="bg-[#002060] border border-dotted border-white px-2 py-1"> </th>
                </tr>
            </thead>
        </table>
        <!-- Contenido de la tabla -->
    <table class="w-full text-center text-xs border border-dotted border-white text-[16px]">
        <thead>
            <tr class="bg-[#002060] text-white">
                <th class="border border-dotted border-white py-1">NO.</th>
                <th class="border border-dotted border-white py-1">TEMA</th>
                <th class="border border-dotted border-white py-1">ACCIÓN</th>
                <th class="border border-dotted border-white py-1">TIENE<br>COSTO</th>
                <th class="border border-dotted border-white py-1">NIVEL DE<br>PRIORIDAD</th>
            </tr>
        </thead>

        <tbody class="border border-dotted border-black">
            {{-- 🔹 Recorre cada SECCIÓN --}}
            @foreach ($acciones as $titulo => $temas)
                <tr class="bg-[#FDE9D9] font-bold text-center border border-dotted border-black">
                    <td colspan="5" class="py-1 font-bold border border-dotted border-black">{{ strtoupper($titulo) }}</td>
                </tr>

                {{-- 🔹 Recorre cada TEMA dentro de la sección --}}
                @foreach ($temas as $index => $r)
                    <tr>
                        {{-- Número --}}
                        <td class="border border-dotted border-black py-1 align-top w-[60px]">
                           
                        </td>

                        {{-- Tema --}}
                        <td class="border border-dotted border-black text-left px-2 align-top">
                            {{ $r['tema'] }}
                        </td>

                        {{-- Acción (Quill + select + botón) --}}
                        <td class="border border-dotted border-black text-left px-2 align-top" width="700">
                            <div
                                x-data="{
                                    initQuill() {
                                        const root = this.$refs.editor;
                                        if (root.dataset.inited === '1') return;

                                        const quill = new Quill(root, {
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

                                        const hidden = this.$refs.textarea;

                                        // Espera un pequeño retraso para asegurar que Livewire ya haya puesto el valor
                                        setTimeout(() => {
                                            if (hidden.value) {
                                                quill.root.innerHTML = hidden.value;
                                            }
                                        }, 200);

                                        quill.on('text-change', () => {
                                            hidden.value = quill.root.innerHTML;
                                            hidden.dispatchEvent(new Event('input'));
                                        });

                                        root.dataset.inited = '1';
                                    }
                                }"
                                x-init="initQuill()"
                            >
                                <div x-ref="editor" style="height:150px; background:white;" wire:ignore></div>
                                <textarea x-ref="textarea"
                                    class="hidden"
                                    wire:model.defer="acciones.{{ $titulo }}.{{ $index }}.accion"></textarea>
                            </div>
                        </td>

                        {{-- Tiene costo --}}
                        <td class="border border-dotted border-black align-top font-semibold" width="100">
                                    <div class="flex items-center ">
                                        <label class="text-xs font-semibold">Tiene costo:</label>
                                        <select class="border rounded px-2 py-1 text-sm"
                                            wire:model.defer="acciones.{{ $titulo }}.{{ $index }}.t_costo">
                                            <option value="" hidden>Seleccione…</option>
                                            <option value="SI">SI</option>
                                            <option value="NO">NO</option>
                                            <option value="N/A">N/A</option>
                                        </select>
                                    </div>

                        </td>

                        {{-- Nivel de prioridad --}}
                        <td class="nivel-celda border border-dotted border-black font-bold align-top text-center" width="100">
                            <select 
                                class="border rounded px-2 py-1 text-sm w-full"
                                wire:model.defer="acciones.{{ $titulo }}.{{ $index }}.nivel_p"
                                onchange="cambiarColorCelda(this)"
                            >
                                <option value="" hidden>Seleccione…</option>
                                <option value="bajo">Bajo</option>
                                <option value="medio">Medio</option>
                                <option value="urgente">Urgente</option>
                                <option value="N/A">N/A</option>
                            </select>

                        </td>

                        <script>
                        function cambiarColorCelda(select) {
                            const td = select.closest('td');
                            td.classList.remove('bg-[#C00000]', 'text-white', 'bg-[#FFFF00]', 'text-black', 'bg-[#00B0F0]');
                            switch (select.value) {
                                case 'urgente':
                                    td.classList.add('bg-[#C00000]', 'text-black');
                                    break;
                                case 'medio':
                                    td.classList.add('bg-[#FFFF00]', 'text-black');
                                    break;
                                case 'bajo':
                                    td.classList.add('bg-[#00B0F0]', 'text-black');
                                    break;
                                case 'N/A':
                                td.classList.add('bg-[#FFFFFFFF]', 'text-white');
                                    break;
                            }
                        }
                        </script>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
        </div>
    @endif
    <br>
    @if ($titulo==42)
    <table style="width: 100%; border-collapse: collapse; text-align: center; font-weight: bold;">
            <tr>
                <td style="border: 1px dashed #999; padding: 8px;">
                    Revisó:
                </td>
                <td style="border: 1px dashed #999; padding: 8px;">
                    Elaboró:
                </td>
            </tr>
            <tr>
                <td style="border: 1px dashed #999; padding: 8px;">
                <input wire:model="puesto_r" id="puesto_r" type="text"
               class="bg-white w-full rounded-radius border border-outline px-2 py-2 text-sm"
               placeholder="Ingrese el puesto del revisor"
               style="border-color:rgba(31, 89, 177, 1);" />
                </td>
                <td style="border: 1px dashed #999; padding: 8px;">
                <input wire:model="puesto_e" id="puesto_e" type="text"
               class="bg-white w-full rounded-radius border border-outline px-2 py-2 text-sm"
               placeholder="Ingrese el puesto de quien elaboró"
               style="border-color:rgba(31, 89, 177, 1);" />
                </td>
            </tr>
            <tr>
                <td style="border: 1px dashed #999; padding: 8px;">
                    <br><br>
                </td>
                <td style="border: 1px dashed #999; padding: 8px;">
                    <br><br>
                </td>
            </tr>
            <tr>
                <td style="border: 1px dashed #999; padding: 8px;">
                <input wire:model="nombre_r" id="nombre_r" type="text"
               class="bg-white w-full rounded-radius border border-outline px-2 py-2 text-sm"
               placeholder="Ingrese el nombre de quien revisó"
               style="border-color:rgba(31, 89, 177, 1);" />
                </td>
                <td style="border: 1px dashed #999; padding: 8px;">
                <input wire:model="nombre_e" id="nombre_e" type="text"
               class="bg-white w-full rounded-radius border border-outline px-2 py-2 text-sm"
               placeholder="Ingrese el nombre de quien elaboró"
               style="border-color:rgba(31, 89, 177, 1);" />
                </td>
            </tr>
            <tr>
                <td style="border: 1px dashed #999; padding: 8px;">
                    <br>
                </td>
                <td style="border: 1px dashed #999; padding: 8px;">
                    <br>
                </td>
            </tr>
            <tr>
                <td style="border: 1px dashed #999; padding: 8px;">
                    Conforme:
                </td>
                <td style="border: 1px dashed #999; padding: 8px;">
                    Autorizó:
                </td>
            </tr>
            <tr>
                <td style="border: 1px dashed #999; padding: 8px;">
                <input wire:model="puesto_c" id="puesto_c" type="text"
               class="bg-white w-full rounded-radius border border-outline px-2 py-2 text-sm"
               placeholder="Ingrese el puesto del conforme"
               style="border-color:rgba(31, 89, 177, 1);" />
                </td>
                <td style="border: 1px dashed #999; padding: 8px;">
                    Secretario de Seguridad del Pueblo de Chiapas
                </td>
            </tr>
            <tr>
                <td style="border: 1px dashed #999; padding: 8px;">
                    <br><br>
                </td>
                <td style="border: 1px dashed #999; padding: 8px;">
                    <br><br>
                </td>
            </tr>
            <tr>
                <td style="border: 1px dashed #999; padding: 8px;">
                <input wire:model="nombre_c" id="nombre_c" type="text"
               class="bg-white w-full rounded-radius border border-outline px-2 py-2 text-sm"
               placeholder="Ingrese el nombre del conforme"
               style="border-color:rgba(31, 89, 177, 1);" />
                </td>
                <td style="border: 1px dashed #999; padding: 8px;">
                    Dr. y P.A. Oscar Alberto Aparicio Avendaño
                </td>
            </tr>
        </table>
    @endif
    <br>
    <button type="submit" class="inline-flex justify-center items-center gap-2 rounded-radius bg-success border border-success px-4 py-2 text-sm font-medium text-on-success transition hover:opacity-75">
        Guardar
    </button>
</div> 