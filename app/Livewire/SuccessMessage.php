<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class SuccessMessage extends Component
{
    // #[On('added')]
    public function render()
    {
        return view('livewire.success-message');
    }
}
