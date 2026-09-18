<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class Previewer extends Component
{
    public string $url = '';

    #[On('refresh')]
    public function render()
    {
        return view('livewire.previewer');
    }
}
