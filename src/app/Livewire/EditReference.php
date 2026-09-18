<?php

namespace App\Livewire;

use App\Livewire\Forms\EditReferenceForm;
use App\Models\Reference;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class EditReference extends ModalComponent
{
    public EditReferenceForm $form;

    public Reference $reference;

    public function mount()
    {
        Gate::authorize('view', $this->reference);
        $this->form->name = $this->reference->name;
        $this->form->position = $this->reference->position;
        $this->form->company = $this->reference->company;
        $this->form->email = $this->reference->email;
        $this->form->phone = $this->reference->phone;
        $this->form->active = $this->reference->active;
    }

    public function submit()
    {
        Gate::authorize('update', $this->reference);
        $this->form->update($this->reference);
        $this->dispatch('refresh');
        $this->dispatch('closeModal');
    }
    public function render()
    {
        return view('livewire.references.edit');
    }
}
