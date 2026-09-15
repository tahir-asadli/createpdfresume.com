<?php

namespace App\Livewire;

use App\Livewire\Forms\EditSocialForm;
use App\Models\Social;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class EditSocial extends ModalComponent
{
    public EditSocialForm $form;
    public Social $social;
    public function render()
    {
        return view('livewire.socials.edit');
    }

    public function mount()
    {
        Gate::authorize('view', $this->social);
        $this->form->site = $this->social->site;
        $this->form->name = $this->social->name ? $this->social->name : '';
        $this->form->handle = $this->social->handle ? $this->social->handle : '';
        $this->form->url = $this->social->url ? $this->social->url : '';
        $this->form->active = $this->social->active;
    }

    public function submit()
    {
        Gate::authorize('update', $this->social);
        $this->form->update($this->social);
        $this->dispatch('refresh');
        $this->dispatch('closeModal');
    }
}
