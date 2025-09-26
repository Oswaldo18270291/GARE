<?php

namespace App\Livewire\History;
use Livewire\WithPagination;
use App\Models\Report;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
class Index extends Component
{
    use WithPagination;
    public function render()
    {
        return view('livewire.history.index',[
            'reports' => Report::where('status', false)
            ->where('user_id', Auth::id()) // 👈 solo del usuario autenticado
            ->paginate(10),
        ]);
    }
}
