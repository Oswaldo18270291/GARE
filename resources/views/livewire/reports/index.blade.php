<div>
    @if (session('eliminar'))
        <!-- danger Alert -->
        <div x-data="{ alertIsVisible: true }" x-show="alertIsVisible" class="relative w-full overflow-hidden rounded-radius border border-danger bg-surface text-on-surface dark:bg-surface-dark dark:text-on-surface-dark" role="alert" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
            <div class="flex w-full items-center gap-2 bg-danger/10 p-4">
                <div class="bg-danger/15 text-danger rounded-full p-1" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-6" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-2">
                    <h3 class="text-sm font-semibold text-danger">Eliminación</h3>
                    <p class="text-xs font-medium sm:text-sm">{{ session('eliminar') }}</p>
                </div>
                <button type="button" @click="alertIsVisible = false" class="ml-auto" aria-label="dismiss alert">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="2.5" class="w-4 h-4 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        <br>
    @endif
    @if (session('success'))
        <!-- success Alert -->
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
                    <h3 class="text-sm font-semibold text-success">Mensaje de creación de informe</h3>
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
    @if (session('up'))
        <!-- success Alert -->
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
                    <h3 class="text-sm font-semibold text-success">Mensaje de actualización de informe</h3>
                    <p class="text-xs font-medium sm:text-sm">{{ session('up') }}</p>
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
            <h1 class="text-white font-sans font-bond text-lg">MIS REPORTES</h1>
        </div>

        <div class="overflow-hidden w-full overflow-x-auto rounded-lg border border-outline dark:border-outline-dark" style="border-color:rgba(31, 89, 177, 1);">
            <table class="w-full text-left text-sm text-on-surface dark:text-on-surface-dark">
                <thead class="border-b border-outline bg-surface-alt text-sm text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong" style="border-color:rgba(53, 118, 216, 1);">
                    <tr>
                        <th scope="col" class="p-4 text-center">Nombre de empresa</th>
                        <th scope="col" class="p-4 text-center">Fecha de creacion</th>
                        <th scope="col" class="p-4 text-center">Representante</th>
                        <th scope="col" class="p-4 text-center">Fecha de analisis</th>
                        <th scope="col" class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline dark:divide-outline-dark">
                @forelse ($reports as $report)
                    <tr class="even:bg-primary/5 dark:even:bg-primary-dark/10" style="border-color:rgba(53, 118, 216, 1);">
                        <td class="p-4 text-center">{{$report->nombre_empresa}}</td>
                        <td class="p-4 text-center">{{$report->created_at}}</td>
                        <td class="p-4 text-center">{{$report->representante}}</td>
                        <td class="p-4 text-center">{{$report->fecha_analisis}}</td>
                        <td class="p-4">
                            <div class="flex justify-center space-x-4 flex-row">
                                <!-- Íconos -->
                                <button 
                                    wire:click="addcontent({{$report->id}})"
                                    class="text-red-800 hover:text-red-600 cursor-pointer transition-colors duration-200"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                    viewBox="0 0 24 24" 
                                    fill="currentColor" 
                                    class="size-6">
                                        <title>Agregar contenido</title>
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <button 
                                    wire:click="editeditstructure({{$report->id}})"
                                    class="text-red-800 hover:text-red-600 cursor-pointer transition-colors duration-200"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                    viewBox="0 0 24 24" 
                                    fill="currentColor" 
                                    class="size-6">
                                        <title>Editar estructura</title>
                                        <path fill-rule="evenodd" d="M7.502 6h7.128A3.375 3.375 0 0 1 18 9.375v9.375a3 3 0 0 0 3-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 0 0-.673-.05A3 3 0 0 0 15 1.5h-1.5a3 3 0 0 0-2.663 1.618c-.225.015-.45.032-.673.05C8.662 3.295 7.554 4.542 7.502 6ZM13.5 3A1.5 1.5 0 0 0 12 4.5h4.5A1.5 1.5 0 0 0 15 3h-1.5Z" clip-rule="evenodd" />
                                        <path fill-rule="evenodd" d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 0 1 3 20.625V9.375ZM6 12a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V12Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75ZM6 15a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V15Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75ZM6 18a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V18Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <button 
                                    onclick="window.open('{{ route('reporte.pdf', $report->id) }}', '_blank')" 
                                    class="text-red-800 hover:text-red-600 cursor-pointer transition-colors duration-200"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                        viewBox="0 0 24 24" 
                                        fill="currentColor" 
                                        class="size-6">
                                        <title>Ver documento</title>
                                        <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                        <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <button 
                                    wire:click="status({{$report->id}})"
                                    wire:confirm="¿Estás seguro que desea finalizar?"
                                    class="text-green-600 hover:text-green-800 cursor-pointer transition-colors duration-200"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                    viewBox="0 0 24 24" 
                                    fill="currentColor" 
                                    class="size-6">
                                        <title>Finalizar</title>
                                        <path fill-rule="evenodd" d="M9 1.5H5.625c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5Zm6.61 10.936a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 14.47a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                                        <path d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                                    </svg>
                                </button>
                                <button 
                                    wire:click="delete({{$report->id}})"
                                    wire:confirm="¿Estás seguro de eliminar este reporte?"
                                    class="text-red-600 hover:text-red-800 cursor-pointer transition-colors duration-200"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                    viewBox="0 0 24 24" 
                                    fill="currentColor" 
                                    class="size-6">
                                        <title>Eliminar</title>
                                        <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
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
