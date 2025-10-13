<div>
@if (session('cont'))
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
                    <h3 class="text-sm font-semibold text-success">Mensaje de información</h3>
                    <p class="text-xs font-medium sm:text-sm">{{ session('cont') }}</p>
                </div>
                <button type="button" @click="alertIsVisible = false" class="ml-auto" aria-label="dismiss alert">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2.5" class="w-4 h-4 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif
@if (session('eliminar'))
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
                    <h3 class="text-sm font-semibold text-success">Mensaje de información</h3>
                    <p class="text-xs font-medium sm:text-sm">{{ session('eliminar') }}</p>
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
    {{-- Encabezado --}}
    <div class="mb-4 flex flex-col items-center justify-center p-3 rounded-lg" style="background-color: rgba(39, 68, 112, 1);">
        <h1 class="text-white font-sans font-bond text-lg">
            Reporte: {{ $report->nombre_empresa }}
        </h1>
        <h2 class="mb-4 text-center text-white">
            Ubicación: {{ $report->ubicacion }}
        </h2>
    </div>

    {{-- Lista de contenido --}}
    <ul class="list-none space-y-4">
        @foreach ($report->titles as $title)
            <li>
                {{-- Título --}}
                <div class="flex justify-between items-center border-b border-gray-500">
                    <h2 class="text-xl font-semibold py-2">
                        {{ $loop->iteration }}. {{ $title->title->nombre }}
                    </h2>
                    <div class="flex space-x-2">
                        <button
                            class="px-3 py-1 border border-emerald-600 rounded 
                                text-emerald-600 
                                hover:bg-emerald-600 hover:text-white 
                                disabled:bg-gray-400 disabled:text-gray-200 disabled:border-gray-400 disabled:cursor-not-allowed"
                            wire:click="Addc('{{$title->id}}','tit','{{ $report->id }}')"
                            @if(\App\Models\Content::where('r_t_id', $title->id)->exists()) disabled @endif
                            >
                            Agregar
                        </button>
                        <button 
                            class="px-3 py-1 border rounded 
                                @if(\App\Models\Content::where('r_t_id', $title->id)->doesntExist()) 
                                    border-gray-400 text-gray-400 bg-gray-100 cursor-not-allowed 
                                @else 
                                    border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white 
                                @endif" 
                            wire:click="Editc({{ $title->id }}, 'tit', {{ $report->id }})"
                            @if(\App\Models\Content::where('r_t_id', $title->id)->doesntExist()) disabled @endif
                        > 
                            Editar
                        </button>
                         <button   
                                 class="px-3 py-1 border rounded 
                                @if(\App\Models\Content::where('r_t_id', $title->id)->doesntExist()) 
                                    border-gray-400 text-gray-400 bg-gray-100 cursor-not-allowed 
                                @else 
                                    border-red-500 text-red-500 hover:bg-red-500 hover:text-white 
                                @endif" 
                                    wire:click="deleteContent({{ $title->id }}, 'tit', {{ $report->id }})"
                                    wire:confirm="¿Estás seguro de que deseas eliminar este contenido?\n\nEsta acción no se puede deshacer y el contenido se eliminará de forma permanente del documento."
                                    @if(\App\Models\Content::where('r_t_id', $title->id)->doesntExist()) disabled @endif
                                        >
                         Eliminar
                         </button>
                    </div>
                </div>

                {{-- Subtítulos dentro del título --}}
                <ul class="list-none ml-6 space-y-2">
                    @foreach ($title->subtitles as $subtitle)
                        <li>
                            <div class="flex justify-between items-center border-b border-gray-400">
                                <h3 class="text-lg font-medium py-2">
                                    {{ $loop->parent->iteration }}.{{ $loop->iteration }} {{ $subtitle->subtitle->nombre }}
                                </h3>
                                <div class="flex space-x-2">
                                    <button
                                        class="px-3 py-1 border border-emerald-600 rounded 
                                            text-emerald-600 
                                            hover:bg-emerald-600 hover:text-white 
                                            disabled:bg-gray-400 disabled:text-gray-200 disabled:border-gray-400 disabled:cursor-not-allowed"
                                        wire:click="Addc({{ $subtitle->id }}, 'sub', {{ $report->id }})"
                                        @if(\App\Models\Content::where('r_t_s_id', $subtitle->id)->exists()) disabled @endif
                                        >
                                        Agregar
                                    </button>
                                    <button 
                                        class="px-3 py-1 border rounded 
                                            @if(\App\Models\Content::where('r_t_s_id', $subtitle->id)->doesntExist()) 
                                                border-gray-400 text-gray-400 bg-gray-100 cursor-not-allowed 
                                            @else 
                                                border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white 
                                            @endif" 
                                        wire:click="Editc({{ $subtitle->id }}, 'sub', {{ $report->id }})"
                                        @if(\App\Models\Content::where('r_t_s_id', $subtitle->id)->doesntExist()) disabled @endif
                                    >
                                        Editar
                                    </button>
                                    <button   
                                            class="px-3 py-1 border rounded 
                                            @if(\App\Models\Content::where('r_t_s_id', $subtitle->id)->doesntExist()) 
                                                border-gray-400 text-gray-400 bg-gray-100 cursor-not-allowed 
                                            @else 
                                                border-red-500 text-red-500 hover:bg-red-500 hover:text-white 
                                            @endif" 
                                        wire:confirm="¿Estás seguro de que deseas eliminar este contenido?\n\nEsta acción no se puede deshacer y el contenido se eliminará de forma permanente del documento."
                                        wire:click="deleteContent({{ $subtitle->id }}, 'sub', {{ $report->id }})"
                                        @if(\App\Models\Content::where('r_t_s_id', $subtitle->id)->doesntExist()) disabled @endif
                                        >
                                    Eliminar
                                    </button>                                
                                    </div>
                            </div>

                            {{-- Secciones dentro del subtítulo --}}
                            <ul class="list-none ml-8 space-y-1">
                                @foreach ($subtitle->sections as $section)
                                    <li>
                                        <div class="flex justify-between items-cente border-b border-gray-400">
                                            <p>
                                                {{ $loop->parent->parent->iteration }}.{{ $loop->parent->iteration }}.{{ $loop->iteration }}
                                                {{ $section->section->nombre }}
                                            </p>
                                            <div class="flex space-x-2">
                                                <button
                                                    class="px-3 py-1 border border-emerald-600 rounded 
                                                        text-emerald-600 
                                                        hover:bg-emerald-600 hover:text-white 
                                                        disabled:bg-gray-400 disabled:text-gray-200 disabled:border-gray-400 disabled:cursor-not-allowed"
                                                    wire:click="Addc({{$section->id}},'sec',{{ $report->id }})"
                                                    @if(\App\Models\Content::where('r_t_s_s_id', $section->id)->exists()) disabled @endif
                                                    >
                                                    Agregar
                                                </button>
                                                <button 
                                                    class="px-3 py-1 border rounded 
                                                        @if(\App\Models\Content::where('r_t_s_s_id', $section->id)->doesntExist()) 
                                                            border-gray-400 text-gray-400 bg-gray-100 cursor-not-allowed 
                                                        @else 
                                                            border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white 
                                                        @endif" 
                                                    wire:click="Editc({{ $section->id }}, 'sec', {{ $report->id }})"
                                                    @if(\App\Models\Content::where('r_t_s_s_id', $section->id)->doesntExist()) disabled @endif
                                                >
                                                    Editar
                                                </button>
                                                <button class="px-3 py-1 border rounded 
                                                        @if(\App\Models\Content::where('r_t_s_s_id', $section->id)->doesntExist()) 
                                                            border-gray-400 text-gray-400 bg-gray-100 cursor-not-allowed 
                                                        @else 
                                                            border-red-500 text-red-500 hover:bg-red-500 hover:text-white 
                                                        @endif"
                                                    wire:confirm="¿Estás seguro de que deseas eliminar este contenido?\n\nEsta acción no se puede deshacer y el contenido se eliminará de forma permanente del documento." 
                                                    wire:click="deleteContent({{ $section->id }}, 'sec', {{ $report->id }})"
                                                    @if(\App\Models\Content::where('r_t_s_s_id', $section->id)->doesntExist()) disabled @endif
                                                >
                                                Eliminar
                                                </button>
                                             </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
</div>
