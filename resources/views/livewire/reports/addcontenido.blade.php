<div>
<div>
    <h1 class="text-2xl font-bold">Reporte: {{ $report->nombre_empresa }}</h1>
    <p class="mb-4">Ubicación: {{ $report->ubicacion }}</p>

    @foreach ($report->titles as $title)
        <div class="mb-4">
            <h2 class="text-xl font-semibold">Título: {{ $title->title->nombre }}</h2>

            @foreach ($title->subtitles as $subtitle)
                <div class="ml-4 mb-2">
                    <h3 class="text-lg font-medium">Subtítulo: {{ $subtitle->subtitle->nombre }}</h3>

                    @foreach ($subtitle->sections as $section)
                        <p class="ml-8">Sección: {{ $section->section->nombre }}</p>
                    @endforeach
                </div>
            @endforeach
        </div>
    @endforeach
</div>

</div>
