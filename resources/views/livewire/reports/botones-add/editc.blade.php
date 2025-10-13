<div>
    @if ($boton == 'tit')
    <form wire:submit.prevent="update('{{ $RTitle->id}}', '{{ $boton }}','{{ $rp }}')" class="w-full p-6 bg-surface-alt dark:bg-surface-dark-alt rounded-lg shadow-md">
        <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
            <label class="w-full pl-0.5 text-2x1 font-sans font-extrabold">{{$RTitle->title->nombre}}</label>
        </div>
        @include('livewire.reports.botones-add.component_editc',[
                    'titulo' => $RTitle->title->nombre,
                ])
    </form>

    @elseif ($boton == 'sub')
    <form wire:submit.prevent="update('{{ $RSubtitle->id }}', '{{ $boton }}','{{ $rp }}')" class="w-full p-6 bg-surface-alt dark:bg-surface-dark-alt rounded-lg shadow-md">
        <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
            <label class="font-extrabold">{{$RSubtitle->reportTitle->title->nombre}}</label>
        </div>
        <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
            <label class="font-semibold">{{$RSubtitle->subtitle->nombre}}</label>
        </div>
        @include('livewire.reports.botones-add.component_editc',[
                    'titulo' => $RSubtitle->subtitle->id,
                    'risks' => $risks,
                ]) 
    </form>

    @elseif ($boton == 'sec')
    <form wire:submit.prevent="update('{{ $RSection->id }}', '{{ $boton }}','{{ $rp }}')" class="w-full p-6 bg-surface-alt dark:bg-surface-dark-alt rounded-lg shadow-md">
        
        <div class="flex w-full flex-col gap-1">
            <label class="font-extrabold">{{$RSection->reportTitleSubtitle->reportTitle->title->nombre}}</label>
        </div>
        <div class="flex w-full flex-col gap-1">
            <label class="font-semibold">{{$RSection->reportTitleSubtitle->subtitle->nombre}}</label>
        </div>
        <div class="flex w-full flex-col gap-1">
            <label>{{$RSection->section->nombre}}</label>

        </div>

            @include('livewire.reports.botones-add.component_editc',[
                    'titulo' => $RSection->section->nombre,
                ])
   </form>
    @endif
</div>
