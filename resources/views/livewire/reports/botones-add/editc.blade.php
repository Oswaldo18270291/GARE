<div>
    @if ($boton == 'tit')
        <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
            <label for="textInputDefault" class="w-full pl-0.5 text-2x1 font-sans font-extrabold">{{$RTitle->title->nombre}}</label>
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
    @elseif ($boton == 'sub')
        <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
            <label for="textInputDefault" class="w-full pl-0.5 text-2x1 font-sans font-extrabold">{{$RSubtitle->reportTitle->title->nombre}}</label>
        </div>
        <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
            <label for="textInputDefault" class="w-full pl-0.5 text-2x1 font-sans font-semibold">{{$RSubtitle->subtitle->nombre}}</label>
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
    @elseif ($boton == 'sec')
        <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
            <label for="textInputDefault" class="w-full pl-0.5 text-2x1 font-sans font-extrabold">{{$RSection->reportTitleSubtitle->reportTitle->title->nombre}}</label>
        </div>
        <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
            <label for="textInputDefault" class="w-full pl-0.5 text-2x1 font-sans font-semibold">{{$RSection->reportTitleSubtitle->subtitle->nombre}}</label>
        </div>
        <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
            <label for="textInputDefault" class="w-full pl-0.5 text-2x1 font-sans">{{$RSection->section->nombre}}</label>
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
    @endif
    
</div>
