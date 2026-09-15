<?php

namespace App\Livewire;

use App\Livewire\Forms\CreateReferenceForm;
use LivewireUI\Modal\ModalComponent;

class AddReference extends ModalComponent
{
    public CreateReferenceForm $form;

    public function render()
    {
        return view('livewire.references.add');
    }

    public function submit()
    {
        $this->form->save();
        $this->dispatch('refresh');
        $this->dispatch('closeModal');
    }
}
