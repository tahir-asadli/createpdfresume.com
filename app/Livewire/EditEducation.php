<?php

namespace App\Livewire;

use App\Livewire\Forms\EditEducationForm;
use App\Models\Education;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class EditEducation extends ModalComponent
{
    public Education $education;

    public EditEducationForm $form;

    public function mount()
    {
        Gate::authorize('view', $this->education);
        $this->form->school = $this->education->school;
        $this->form->degree = $this->education->degree;
        $this->form->field = $this->education->field ? $this->education->field : '';
        $this->form->location = $this->education->location;
        $this->form->description = $this->education->description;
        $this->form->startDate = $this->education->start_date ? $this->education->start_date->format("d-m-Y") : '';
        $this->form->endDate = $this->education->end_date ? $this->education->end_date->format("d-m-Y") : '';
        $this->form->active = $this->education->active;
    }
    public function render()
    {
        return view('livewire.educations.edit');
    }
    public function submit()
    {
        Gate::authorize('update', $this->education);
        $this->form->update($this->education);
        $this->dispatch('refresh');
        $this->dispatch('closeModal');
    }
}
