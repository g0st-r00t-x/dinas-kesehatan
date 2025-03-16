<?php

namespace App\Livewire;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Livewire\Attributes\On;
use Livewire\Component;

class Sample extends Component
{
    public function render()
    {
        return view('livewire.sample');
    }

    #[On('echo:publicChannel, Test')]

    public function dumb(){
        dd('dump');
    }
}
