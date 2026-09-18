<?php

namespace App\Livewire;

use App\Livewire\Forms\CreateEducationForm;
use LivewireUI\Modal\ModalComponent;

class AddEducation extends ModalComponent
{

    public CreateEducationForm $form;
    public function render()
    {
        return view('livewire.educations.add');
    }

    public function submit()
    {
        $this->form->save();
        $this->dispatch('refresh');
        $this->dispatch('closeModal');
    }
}
