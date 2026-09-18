<?php

namespace App\Livewire;

use App\Livewire\Forms\EditSkillForm;
use App\Models\Skill;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class EditSkill extends ModalComponent
{

    public EditSkillForm $form;
    public Skill $skill;
    public function mount()
    {
        Gate::authorize('view', $this->skill);
        $this->form->name = $this->skill->name;
        $this->form->level = $this->skill->level;
        $this->form->active = $this->skill->active;
    }
    public function render()
    {
        return view('livewire.skills.edit');
    }
    public function submit()
    {
        Gate::authorize('update', $this->skill);
        $this->form->update($this->skill);
        $this->dispatch('refresh');
        $this->dispatch('closeModal');
    }

}
