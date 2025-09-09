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
    document.addEventListener('DOMContentLoaded', function () {
        // Toggle subtítulos al marcar título
        document.querySelectorAll('.toggle-subtitles').forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const subtitlesDiv = this.closest('.title-wrapper').querySelector('.subtitles');
                subtitlesDiv.style.display = this.checked ? 'block' : 'none';
            });
        });

        // Toggle secciones al marcar subtítulo
        document.querySelectorAll('.toggle-sections').forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const sectionsUl = this.closest('.subtitle-wrapper').querySelector('.sections');
                sectionsUl.style.display = this.checked ? 'block' : 'none';
            });
        });
    });
</script>

</div>
