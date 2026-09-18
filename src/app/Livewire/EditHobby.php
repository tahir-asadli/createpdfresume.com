<?php

namespace App\Livewire;

use App\Livewire\Forms\EditHobbyForm;
use App\Models\Hobby;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class EditHobby extends ModalComponent
{
    public EditHobbyForm $form;

    public Hobby $hobby;

    public function mount()
    {
        Gate::authorize('view', $this->hobby);
        $this->form->name = $this->hobby->name;
        $this->form->icon = $this->hobby->icon;
        $this->form->active = $this->hobby->active;
    }

    public function submit()
    {
        Gate::authorize('update', $this->hobby);
        $this->form->update($this->hobby);
        $this->dispatch('refresh');
        $this->dispatch('closeModal');
    }
    public function render()
    {
        return view('livewire.hobbies.edit');
    }
}
