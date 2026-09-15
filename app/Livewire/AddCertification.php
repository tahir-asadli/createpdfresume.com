<?php

namespace App\Livewire;

use App\Livewire\Forms\CreateCertificationForm;
use LivewireUI\Modal\ModalComponent;

class AddCertification extends ModalComponent
{
    public CreateCertificationForm $form;

    public function render()
    {
        return view('livewire.certifications.add');
    }

    public function submit()
    {
        $this->form->save();
        $this->dispatch('refresh');
        $this->dispatch('closeModal');
    }
}
