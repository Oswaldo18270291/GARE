<div>
    @if ($boton == 'tit')
        <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
            <label for="textInputDefault" class="w-fit pl-0.5 text-2x1">{{$RTitle->title->nombre}}</label>
        </div>
        <div 
            x-data 
            x-init="$nextTick(() => {
                new Quill($refs.editor, {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            [{ header: [1, 2, false] }],
                            ['bold', 'italic', 'underline'],
                            [{ 'align': [] }],
                            [{ list: 'ordered'}, { list: 'bullet' }],
                            ['clean']
                        ]
                    }
                });
            })"
        >
            <div x-ref="editor" style="height:200px;"></div>
        </div>

        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script> 
    @elseif ($boton == 'sub')
        <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
            <label for="textInputDefault" class="w-fit pl-0.5 text-2x1">{{$RSubtitle->reportTitle->title->nombre}}</label>
        </div>
        <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
            <label for="textInputDefault" class="w-fit pl-0.5 text-2x1">{{$RSubtitle->subtitle->nombre}}</label>
        </div>
        <div 
            x-data 
            x-init="$nextTick(() => {
                new Quill($refs.editor, {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            [{ header: [1, 2, false] }],
                            ['bold', 'italic', 'underline'],
                            [{ 'align': [] }],
                            [{ list: 'ordered'}, { list: 'bullet' }],
                            ['clean']
                        ]
                    }
                });
            })"
        >
            <div x-ref="editor" style="height:200px;"></div>
        </div>

        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>   
    @elseif ($boton == 'sec')
        <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
            <label for="textInputDefault" class="w-fit pl-0.5 text-2x1">{{$RSection->reportTitleSubtitle->reportTitle->title->nombre}}</label>
        </div>
        <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
            <label for="textInputDefault" class="w-fit pl-0.5 text-2x1">{{$RSection->reportTitleSubtitle->subtitle->nombre}}</label>
        </div>
        <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
            <label for="textInputDefault" class="w-fit pl-0.5 text-2x1">{{$RSection->section->nombre}}</label>
        </div>
        <div 
            x-data 
            x-init="$nextTick(() => {
                new Quill($refs.editor, {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            [{ header: [1, 2, false] }],
                            ['bold', 'italic', 'underline'],
                            [{ 'align': [] }],
                            [{ list: 'ordered'}, { list: 'bullet' }],
                            ['clean']
                        ]
                    }
                });
            })"
        >
            <div x-ref="editor" style="height:200px;"></div>
        </div>

        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script> 
    @endif
    
</div>
