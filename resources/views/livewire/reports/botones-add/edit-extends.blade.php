<div>
    @if ($boton == 'tit')
    <form wire:submit.prevent="update('{{ $RTitle->id}}', '{{ $boton }}','{{ $rp }}')" class="w-full p-6 bg-surface-alt dark:bg-surface-dark-alt rounded-lg shadow-md">
        <div class="mb-6 flex items-center justify-center p-4 rounded-lg" style="background-color: rgba(39, 68, 112, 1);">
            <h1 class="text-white font-sans font-extrabold text-xl">{{$RTitle->title->nombre}}</h2>
        </div>
        @include('livewire.reports.botones-add.component_editc_extends',[
                    'titulo' => $RTitle->title->nombre,
                    'boton' => $boton,
                ])
    </form>
    @endif
</div>
