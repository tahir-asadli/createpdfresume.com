<?php

namespace App\Livewire;

use App\Livewire\Forms\EditProjectForm;
use App\Models\Project;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class EditProject extends ModalComponent
{

    public EditProjectForm $form;

    public Project $project;

    public function mount()
    {
        Gate::authorize('view', $this->project);
        $this->form->name = $this->project->name;
        $this->form->description = $this->project->description;
        $this->form->date = $this->project->date ? $this->project->date->format("d-m-Y") : '';
        $this->form->url = $this->project->url;
        $this->form->active = $this->project->active;
    }

    public function submit()
    {
        Gate::authorize('update', $this->project);
        $this->form->update($this->project);
        $this->dispatch('refresh');
        $this->dispatch('closeModal');
    }

    public function render()
    {
        return view('livewire.projects.edit');
    }
}
