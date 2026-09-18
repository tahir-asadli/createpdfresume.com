<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class Subscription extends Component
{
    #[On('refresh')]
    public function render()
    {
        return view('livewire.subscription');
    }

    public function cancel()
    {
        auth()->user()->cancelSubscription();
        $this->dispatch('refresh');
    }
}
