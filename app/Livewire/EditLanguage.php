<?php

namespace App\Livewire;

use App\Livewire\Forms\EditLanguageForm;
use App\Models\Language;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class EditLanguage extends ModalComponent
{

    public EditLanguageForm $form;
    public Language $language;
    public function mount()
    {
        Gate::authorize('view', $this->language);
        $this->form->name = $this->language->name;
        $this->form->level = $this->language->level;
        $this->form->active = $this->language->active;
    }
    public function render()
    {
        return view('livewire.languages.edit');
    }
    public function submit()
    {
        Gate::authorize('update', $this->language);
        $this->form->update($this->language);
        $this->dispatch('refresh');
        $this->dispatch('closeModal');
    }

}
