<?php

namespace App\Livewire;

use App\Livewire\Forms\CreateAwardForm;
use LivewireUI\Modal\ModalComponent;

class AddAward extends ModalComponent
{
    public CreateAwardForm $form;

    public function render()
    {
        return view('livewire.awards.add');
    }

    public function submit()
    {
        $this->form->save();
        $this->dispatch('refresh');
        $this->dispatch('closeModal');
    }
}
