<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class Orders extends Component
{
    #[On('refresh')]
    public function render()
    {
        return view('livewire.orders');
    }
}
