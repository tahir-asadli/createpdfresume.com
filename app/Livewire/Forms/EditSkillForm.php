<?php

namespace App\Livewire\Forms;

use App\Models\Skill;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EditSkillForm extends Form
{
    #[Validate('required|max:500', 'Skill')]
    public string $name = '';

    #[Validate('required|integer|between:5,100', 'Percentage')]
    public int $level = 5;

    #[Validate('required', 'Enabled')]
    public bool $active = false;

    public function update(Skill $skill)
    {
        $this->validate(attributes: [
            'name' => __('Skill'),
            'level' => __('Percentage'),
            'active' => __('Enabled'),
        ]);
        $data = [
            'name' => cl($this->name),
            'level' => cl($this->level),
            'active' => $this->active,
        ];
        $skill->update($data);
    }
}
