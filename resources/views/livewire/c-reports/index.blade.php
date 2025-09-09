<div>
Creacion de reportes
    
@foreach ($titles as $title)
    <div class="title-wrapper">
        <label>
            <input type="checkbox" class="toggle-subtitles">
            <strong>{{ $title->nombre }}</strong>
        </label>

        <div class="subtitles" style="display: none; margin-left: 20px;">
            @foreach ($title->subtitles as $subtitle)
                <div class="subtitle-wrapper">
                    <label>
                        <input type="checkbox" class="toggle-sections">
                        {{ $subtitle->nombre }}
                    </label>

                    <ul class="sections" style="display: none; margin-left: 20px;">
                        @foreach ($subtitle->sections as $section)
                            <li>
                                <label>
                                    <input type="checkbox" name="sections[]">
                                    {{ $section->nombre }}
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
@endforeach

<!-- JavaScript -->
<script>
    function initCheckboxes() {
        document.querySelectorAll('.toggle-subtitles').forEach(function (checkbox) {
            checkbox.removeEventListener('change', toggleSubtitlesHandler); // Limpieza previa
            checkbox.addEventListener('change', toggleSubtitlesHandler);
        });

        document.querySelectorAll('.toggle-sections').forEach(function (checkbox) {
            checkbox.removeEventListener('change', toggleSectionsHandler); // Limpieza previa
            checkbox.addEventListener('change', toggleSectionsHandler);
        });
    }

    function toggleSubtitlesHandler(event) {
        const subtitlesDiv = event.target.closest('.title-wrapper').querySelector('.subtitles');
        subtitlesDiv.style.display = event.target.checked ? 'block' : 'none';
    }

    function toggleSectionsHandler(event) {
        const sectionsUl = event.target.closest('.subtitle-wrapper').querySelector('.sections');
        sectionsUl.style.display = event.target.checked ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function () {
        initCheckboxes();

        // Escuchar cuando Livewire actualiza el DOM
        Livewire.hook('message.processed', (message, component) => {
            initCheckboxes();
        });
    });
</script>

</div>
