<?php

namespace App\Livewire;

use App\Livewire\Forms\EditCertificationForm;
use App\Models\Certification;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class EditCertification extends ModalComponent
{
    public EditCertificationForm $form;

    public Certification $certification;

    public function mount()
    {
        Gate::authorize('view', $this->certification);
        $this->form->name = $this->certification->name;
        $this->form->description = $this->certification->description;
        $this->form->date = $this->certification->date ? $this->certification->date->format("d-m-Y") : '';
        $this->form->organization = $this->certification->organization;
        $this->form->active = $this->certification->active;
    }

    public function render()
    {
        return view('livewire.certifications.edit');
    }

    public function submit()
    {
        Gate::authorize('update', $this->certification);
        $this->form->update($this->certification);
        $this->dispatch('refresh');
        $this->dispatch('closeModal');
    }
}
