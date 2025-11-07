<div>
    @if ($boton == 'tit')
    <form wire:submit.prevent="store('{{ $RTitle->id}}', '{{ $boton }}','{{ $rp }}')" class="w-full p-6 bg-surface-alt dark:bg-surface-dark-alt rounded-lg shadow-md">
        <div class="mb-6 flex items-center justify-center p-4 rounded-lg" style="background-color: rgba(39, 68, 112, 1);">
            <h1 class="text-white font-sans font-extrabold text-xl">{{$RTitle->title->nombre}}</h2>
        </div>

        @include('livewire.reports.botones-add.component_addc',[
                    'titulo' => $RTitle->title->nombre,
                    'boton' => $boton,
                ])
    </form>

    @elseif ($boton == 'sub')
    
    <form wire:submit.prevent="store('{{ $RSubtitle->id }}', '{{ $boton }}','{{ $rp }}')" class="w-full p-6 bg-surface-alt dark:bg-surface-dark-alt rounded-lg shadow-md">
        <div class="mb-4 flex flex-col items-center justify-center p-3 rounded-lg" style="background-color: rgba(39, 68, 112, 1);">
            <h1 class="text-white font-sans font-extrabold text-lg">{{$RSubtitle->reportTitle->title->nombre}}</h1>
            <h2 class="text-white font-sans font-semibold">{{$RSubtitle->subtitle->nombre}}</h2>
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
        <div class="mb-4 flex flex-col items-center justify-center p-3 rounded-lg" style="background-color: rgba(39, 68, 112, 1);">
            <h1 class="text-white font-sans font-extrabold text-lg">{{$RSection->reportTitleSubtitle->reportTitle->title->nombre}}</h1>
            <h2 class="text-white font-sans font-semibold">{{$RSection->reportTitleSubtitle->subtitle->nombre}}</h2>
            <h3 class="text-white font-sans">{{$RSection->section->nombre}}</h3>
        </div>            

                   @include('livewire.reports.botones-add.component_addc',[
                    'titulo' => $RSection->section->nombre,
                    'boton' => $boton,
                ])
   </form>
    @endif
</div>
