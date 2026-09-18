<?php

namespace App\Livewire;

use App\Livewire\Forms\CreateExperienceForm;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class AddExperience extends ModalComponent
{

    public CreateExperienceForm $form;

    public function render()
    {
        return view('livewire.experiences.add');
    }

    public function submit(){
        $this->form->save();
        $this->dispatch('refresh');
        $this->dispatch('closeModal');
    }
}
