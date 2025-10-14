<div>
    @if ($boton == 'tit')
    <form wire:submit.prevent="store('{{ $RTitle->id}}', '{{ $boton }}','{{ $rp }}')" class="w-full p-6 bg-surface-alt dark:bg-surface-dark-alt rounded-lg shadow-md">
        <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
            <label class="w-full pl-0.5 text-2x1 font-sans font-extrabold">{{$RTitle->title->nombre}}</label>
        </div>

        @include('livewire.reports.botones-add.component_addc',[
                    'titulo' => $RTitle->title->nombre,
                    'boton' => $boton,
                ])
    </form>

    @elseif ($boton == 'sub')
    
    <form wire:submit.prevent="store('{{ $RSubtitle->id }}', '{{ $boton }}','{{ $rp }}')" class="w-full p-6 bg-surface-alt dark:bg-surface-dark-alt rounded-lg shadow-md">
        <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
            <label class="font-extrabold">{{$RSubtitle->reportTitle->title->nombre}}</label>
        </div>
        <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
            <label class="font-semibold">{{$RSubtitle->subtitle->nombre}}</label>
        </div>
                @include('livewire.reports.botones-add.component_addc',[
                    'titulo' => $RSubtitle->subtitle->id,
                    'risks' => $risks,
                    'boton' => $boton,
                    'su' => $su,
                ]) 
    </form>

    @elseif ($boton == 'sec')
    <form wire:submit.prevent="store('{{ $RSection->id }}', '{{ $boton }}','{{ $rp }}')" class="w-full p-6 bg-surface-alt dark:bg-surface-dark-alt rounded-lg shadow-md">
        <div class="flex w-full flex-col gap-1">
            <label class="font-extrabold">{{$RSection->reportTitleSubtitle->reportTitle->title->nombre}}</label>
        </div>
        <div class="flex w-full flex-col gap-1">
            <label class="font-semibold">{{$RSection->reportTitleSubtitle->subtitle->nombre}}</label>
        </div>
        <div class="flex w-full flex-col gap-1">
            <label>{{$RSection->section->nombre}}</label>
        </div>

                   @include('livewire.reports.botones-add.component_addc',[
                    'titulo' => $RSection->section->nombre,
                    'boton' => $boton,
                ])
   </form>
    @endif
</div>
