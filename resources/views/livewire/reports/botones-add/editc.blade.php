<div>
    <form  wire:submit='store' class="w-full p-6 bg-surface-alt dark:bg-surface-dark-alt rounded-lg shadow-md">
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
        <button type="submit" class="inline-flex justify-center items-center gap-2 whitespace-nowrap rounded-radius bg-success border border-success dark:border-success px-4 py-2 text-sm font-medium tracking-wide text-on-success transition hover:opacity-75 text-center focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-success active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-success dark:text-on-success dark:focus-visible:outline-success">
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="size-5 fill-on-success dark:fill-on-success" fill="currentColor">
                <path fill-rule="evenodd" d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z" clip-rule="evenodd" />
            </svg>
            Actualizar
        </button>
    </form>
</div>
