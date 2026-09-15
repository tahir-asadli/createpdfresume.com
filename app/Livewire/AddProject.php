<?php

namespace App\Livewire;

use App\Livewire\Forms\CreateProjectForm;
use LivewireUI\Modal\ModalComponent;

class AddProject extends ModalComponent
{

    public CreateProjectForm $form;

    public function render()
    {
        return view('livewire.projects.add');
    }
    public function submit()
    {
        $this->form->save();
        $this->dispatch('refresh');
        $this->dispatch('closeModal');
    }
}
