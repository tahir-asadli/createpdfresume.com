<?php

namespace App\Livewire\Forms;

use App\Models\Skill;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CreateSkillForm extends Form
{
    #[Validate('required|max:500', 'Skill')]
    public string $name = '';

    #[Validate('required|integer|between:5,100', 'Percentage')]
    public int $level = 100;

    #[Validate('required', 'Enabled')]
    public bool $active = true;


    public function save()
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
        Skill::create(
            $data + ['user_id' => auth()->user()->id]
        );

    }
}
