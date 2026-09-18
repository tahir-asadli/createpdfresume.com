<?php

namespace App\Livewire;

use App\Livewire\Forms\EditAwardForm;
use App\Models\Award;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class EditAward extends ModalComponent
{
    public EditAwardForm $form;

    public Award $award;

    public function mount()
    {
        Gate::authorize('view', $this->award);
        $this->form->name = $this->award->name;
        $this->form->description = $this->award->description;
        $this->form->date = $this->award->date ? $this->award->date->format("d-m-Y") : '';
        $this->form->organization = $this->award->organization;
        $this->form->active = $this->award->active;
    }

    public function submit()
    {
        Gate::authorize('update', $this->award);
        $this->form->update($this->award);
        $this->dispatch('refresh');
        $this->dispatch('closeModal');
    }
    public function render()
    {
        return view('livewire.awards.edit');
    }
}
