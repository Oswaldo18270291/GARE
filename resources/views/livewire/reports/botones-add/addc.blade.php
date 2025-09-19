<div>
    @if ($boton == 'tit')
        <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
            <label for="textInputDefault" class="w-fit pl-0.5 text-2x1">{{$RTitle->title->nombre}}</label>
        </div>
        <!-- Estilos -->
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <!-- Editor -->
        <div id="editor" style="height: 200px;"></div>
        <!-- Script -->
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
        <script>
        var quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
            toolbar: [
                [{ header: [1, 2, false] }],
                ['bold', 'italic', 'underline'],
                //[{ 'color': [] }, { 'background': [] }],
                [{ 'align': [] }],
                [{ list: 'ordered'}, { list: 'bullet' }],
                ['clean']
            ]
            }
        });
        </script> 
    @elseif ($boton == 'sub')
        <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
            <label for="textInputDefault" class="w-fit pl-0.5 text-2x1">sub</label>
        </div>
        <!-- Estilos -->
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <!-- Editor -->
        <div id="editor" style="height: 200px;"></div>
        <!-- Script -->
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
        <script>
        var quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
            toolbar: [
                [{ header: [1, 2, false] }],
                ['bold', 'italic', 'underline'],
                //[{ 'color': [] }, { 'background': [] }],
                [{ 'align': [] }],
                [{ list: 'ordered'}, { list: 'bullet' }],
                ['clean']
            ]
            }
        });
        </script>  
    @elseif ($boton == 'sec')
        <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
            <label for="textInputDefault" class="w-fit pl-0.5 text-2x1">sec</label>
        </div>
        <!-- Estilos -->
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <!-- Editor -->
        <div id="editor" style="height: 200px;"></div>
        <!-- Script -->
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
        <script>
        var quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
            toolbar: [
                [{ header: [1, 2, false] }],
                ['bold', 'italic', 'underline'],
                //[{ 'color': [] }, { 'background': [] }],
                [{ 'align': [] }],
                [{ list: 'ordered'}, { list: 'bullet' }],
                ['clean']
            ]
            }
        });
        </script> 
    @endif
</div>
