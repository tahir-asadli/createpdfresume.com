<?php

namespace App\Livewire;

use App\Livewire\Forms\CreateHobbyForm;
use LivewireUI\Modal\ModalComponent;

class AddHobby extends ModalComponent
{
    public CreateHobbyForm $form;

    public function render()
    {
        return view('livewire.hobbies.add');
    }

    public function submit()
    {
        $this->form->save();
        $this->dispatch('refresh');
        $this->dispatch('closeModal');
    }
}
