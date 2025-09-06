<div>
Creacion de reportes
    
@foreach ($titles as $title)
    <h2>{{ $title->nombre }}</h2>

    @foreach ($title->subtitles as $subtitle)
        <h3>{{ $subtitle->nombre }}</h3>

        <ul>
            @foreach ($subtitle->sections as $section)
                <li>{{ $section->nombre }}</li>
            @endforeach
        </ul>
    @endforeach
@endforeach
</div>
