<?php

namespace App\Livewire;

use Livewire\Component;

class Cancel extends Component
{

    public $errorMessage = '';
    public $successMessage = '';
    public $hasSubscription = false;

    public function mount()
    {
        $this->hasSubscription = auth()->user()->hasSubscription();
    }

    public function submit()
    {
        if (auth()->user()->cancelSubscription()) {
            $this->successMessage = 'Subscription cancelled!';
        } else {
            $this->errorMessage = 'Error occured, please try again!';
        }
    }

    public function render()
    {
        return view('livewire.cancel');
    }
}
