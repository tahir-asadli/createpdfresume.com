<?php

namespace App\Livewire;

use App\Livewire\Forms\CreateSocialForm;
use Livewire\Attributes\Validate;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class AddSocial extends ModalComponent
{

    public CreateSocialForm $form;

    public function render()
    {
        return view('livewire.socials.add');
    }

    public function submit()
    {
        $this->form->save();
        $this->dispatch('refresh');
        $this->dispatch('closeModal');
    }
}
