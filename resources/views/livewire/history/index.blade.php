
<div class="w-full p-6 bg-surface-alt dark:bg-surface-dark-alt rounded-lg shadow-md">
    @if (session('success'))
        <div x-data="{ alertIsVisible: true }" 
             x-show="alertIsVisible" 
             class="relative w-full overflow-hidden rounded-radius border border-success bg-surface text-on-surface dark:bg-surface-dark dark:text-on-surface-dark" 
             role="alert" 
             x-transition:leave="transition ease-in duration-300" 
             x-transition:leave-start="opacity-100 scale-100" 
             x-transition:leave-end="opacity-0 scale-90">

            <div class="flex w-full items-center gap-2 bg-success/10 p-4">
                <div class="bg-success/15 text-success rounded-full p-1" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-2">
                    <h3 class="text-sm font-semibold text-success">Mensaje de Finalización</h3>
                    <p class="text-xs font-medium sm:text-sm">{{ session('success') }}</p>
                </div>
                <button type="button" @click="alertIsVisible = false" class="ml-auto" aria-label="dismiss alert">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2.5" class="w-4 h-4 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        <br>
    @endif
    <div>
        <div class="mb-4 flex items-center justify-center p-4 rounded-lg" style="background-color: rgba(39, 68, 112, 1);">
            <h1 class="text-white font-sans font-bond text-lg">REPORTES FINALIZADOS</h1>
        </div>

 {{-- Buscador y filtro por fechas --}}
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        {{-- Buscador --}}
        <input type="text"
               wire:model.live="search"
               placeholder="Buscar reporte..."
               class="bg-white border px-3 py-2 rounded w-full sm:w-1/3 focus:outline-none focus:ring focus:ring-blue-300"
               style="border-color:rgba(31, 89, 177, 1);">

        {{-- Fechas --}}
        <div class="flex gap-2">
            <div>
                <label class="block text-sm font-medium">Desde:</label>
                <input type="date" wire:model.live="startDate"
                       class="bg-white border px-3 py-2 rounded focus:outline-none focus:ring focus:ring-blue-300"
                       style="border-color:rgba(31, 89, 177, 1);">
            </div>
            <div>
                <label class="block text-sm font-medium">Hasta:</label>
                <input type="date" wire:model.live="endDate"
                       class="bg-white border px-3 py-2 rounded focus:outline-none focus:ring focus:ring-blue-300"
                       style="border-color:rgba(31, 89, 177, 1);">
            </div>
        </div>
    </div>
        <div class="overflow-hidden w-full overflow-x-auto rounded-lg border border-outline dark:border-outline-dark" style="border-color:rgba(31, 89, 177, 1);">
            
            <table class="bg-white w-full text-left text-sm text-on-surface dark:text-on-surface-dark">
                <thead class="border-b border-outline bg-surface-alt text-sm text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong" style="border-color:rgba(31, 89, 177, 1);">
                    <tr>
                        <th scope="col" class="p-4 text-center">Nombre de empresa</th>
                         <th class="p-4 text-center cursor-pointer" wire:click="sortBy('created_at')">
                            Fecha de creación
                            @if($sortField === 'created_at')
                                @if($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            @endif
                        </th>
                        <th scope="col" class="p-4 text-center">Representante</th>
                        <th class="p-4 text-center cursor-pointer" wire:click="sortBy('fecha_analisis')">
                            Fecha de análisis
                            @if($sortField === 'fecha_analisis')
                                @if($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            @endif
                        </th>
                        <th scope="col" class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline dark:divide-outline-dark">
                @forelse ($reports as $report)
                    <tr class="even:bg-primary/5 dark:even:bg-primary-dark/10" style="border-color:rgba(53, 118, 216, 0.68);">
                        <td class="p-4 text-center">{{$report->nombre_empresa}}</td>
                        <td class="p-4 text-center">{{$report->created_at}}</td>
                        <td class="p-4 text-center">{{$report->representante}}</td>
                        <td class="p-4 text-center">{{$report->fecha_analisis}}</td>
                        <td class="p-4">
                            <div class="flex justify-center space-x-4 flex-row">
                                <!-- Íconos -->
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="rgba(143, 6, 6, 1)" class="size-6">
                                    <title>Descargar</title>
                                    <path fill-rule="evenodd" d="M5.625 1.5H9a3.75 3.75 0 0 1 3.75 3.75v1.875c0 1.036.84 1.875 1.875 1.875H16.5a3.75 3.75 0 0 1 3.75 3.75v7.875c0 1.035-.84 1.875-1.875 1.875H5.625a1.875 1.875 0 0 1-1.875-1.875V3.375c0-1.036.84-1.875 1.875-1.875Zm5.845 17.03a.75.75 0 0 0 1.06 0l3-3a.75.75 0 1 0-1.06-1.06l-1.72 1.72V12a.75.75 0 0 0-1.5 0v4.19l-1.72-1.72a.75.75 0 0 0-1.06 1.06l3 3Z" clip-rule="evenodd" />
                                    <path d="M14.25 5.25a5.23 5.23 0 0 0-1.279-3.434 9.768 9.768 0 0 1 6.963 6.963A5.23 5.23 0 0 0 16.5 7.5h-1.875a.375.375 0 0 1-.375-.375V5.25Z" />
                                </svg>

                                <!-- agrega los demás SVG aquí -->
                            </div>
                        </td>
                    </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-4 text-center"> Informes no encontrados</td>
                </tr>
                @endforelse
                </tbody>
            </table>
            <div class="p-4">
            {{ $reports->links() }}
            </div>
        </div>
    </div>
</div>
