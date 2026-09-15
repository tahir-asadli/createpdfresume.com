<?php

namespace App\Livewire;

use App\Livewire\Forms\CreateLanguageForm;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class AddLanguage extends ModalComponent
{

  public CreateLanguageForm $form;

  public function submit()
  {
    $this->form->save();
    $this->dispatch('refresh');
    $this->dispatch('closeModal');
  }
  public function render()
  {
    return view('livewire.languages.add');
  }
}
