<div>
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
                        <button class="px-3 py-1 border border-emerald-600 rounded text-emerald-600 hover:bg-emerald-600 hover:text-white">Agregar</button>
                        <button class="px-3 py-1 border border-blue-500 rounded text-blue-500 hover:bg-blue-500 hover:text-white">Editar</button>
                        <button class="px-3 py-1 border border-red-500 rounded text-red-500 hover:bg-red-500 hover:text-white">Eliminar</button>
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
                                    <button class="px-3 py-1 border border-emerald-600 rounded text-emerald-600 hover:bg-emerald-600 hover:text-white">Agregar</button>
                                    <button class="px-3 py-1 border border-blue-500 rounded text-blue-500 hover:bg-blue-500 hover:text-white">Editar</button>
                                    <button class="px-3 py-1 border border-red-500 rounded text-red-500 hover:bg-red-500 hover:text-white">Eliminar</button>
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
                                                <button class="px-3 py-1 border border-emerald-600 rounded text-emerald-600 hover:bg-emerald-600 hover:text-white">Agregar</button>
                                                <button class="px-3 py-1 border border-blue-500 rounded text-blue-500 hover:bg-blue-500 hover:text-white">Editar</button>
                                                <button class="px-3 py-1 border border-red-500 rounded text-red-500 hover:bg-red-500 hover:text-white">Eliminar</button>
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
