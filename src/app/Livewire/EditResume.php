<?php

namespace App\Livewire;

use App\Livewire\Forms\EditResumeForm;
use App\Models\Resume;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class EditResume extends ModalComponent
{
    public EditResumeForm $form;

    public Resume $resume;

    public function mount()
    {
        Gate::authorize('view', $this->resume);
        $this->form->name = $this->resume->name;
        // $this->form->uuid = $this->resume->template->uuid;
    }
    public function render()
    {
        return view('livewire.resumes.edit');
    }
    public function submit()
    {
        Gate::authorize('update', $this->resume);
        $this->form->update($this->resume);
        $this->dispatch('refresh');
        $this->dispatch('closeModal');
    }
}
