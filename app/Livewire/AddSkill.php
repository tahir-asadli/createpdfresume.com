<?php

namespace App\Livewire;

use App\Livewire\Forms\CreateSkillForm;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class AddSkill extends ModalComponent
{

    public CreateSkillForm $form;

    public function submit()
    {
        $this->form->save();
        $this->dispatch('refresh');
        $this->dispatch('closeModal');
    }
    public function render()
    {
        return view('livewire.skills.add');
    }
}
