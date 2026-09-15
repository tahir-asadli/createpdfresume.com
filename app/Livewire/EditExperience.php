<?php

namespace App\Livewire;

use App\Livewire\Forms\EditExperienceForm;
use App\Models\Experience;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Validate;
use LivewireUI\Modal\ModalComponent;

class EditExperience extends ModalComponent
{

    public Experience $experience;

    public EditExperienceForm $form;

    public function mount()
    {
        Gate::authorize('view', $this->experience);
        $this->form->company = $this->experience->company;
        $this->form->position = $this->experience->position;
        $this->form->about = $this->experience->about;
        $this->form->location = $this->experience->location;
        $this->form->startDate = $this->experience->start_date ? $this->experience->start_date->format("d-m-Y") : '';
        $this->form->endDate = $this->experience->end_date ? $this->experience->end_date->format("d-m-Y") : '';
        $this->form->active = $this->experience->active;
    }

    public function render()
    {
        return view('livewire.experiences.edit');
    }
    public function submit()
    {
        Gate::authorize('update', $this->experience);
        $this->form->update($this->experience);
        $this->dispatch('refresh');
        $this->dispatch('closeModal');
    }
}
