<?php

namespace App\Livewire\Forms;

use App\Models\Hobby;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CreateHobbyForm extends Form
{
    #[Validate('required|max:60', 'Hobby name')]
    public $name = '';

    #[Validate('required|max:30', 'Icon')]
    public $icon = '';

    #[Validate('required', 'Enabled')]
    public bool $active = true;

    public function save()
    {
        $hobbies = collect(array_keys(config('site.hobbies')))->map(fn($a) => str_replace(' ', '_', strtolower($a)));
        $this->validate(rules: [
            'name' => 'required|max:60',
            'icon' => "required|max:30|" . Rule::in($hobbies),
            'active' => 'required',
        ], attributes: [
            'name' => __('Hobby name'),
            'icon' => __('Icon'),
            'active' => __('Enabled'),
        ]);

        Hobby::create(
            [
                'name' => cl($this->name),
                'icon' => cl($this->icon),
                'active' => $this->active,
                'user_id' => auth()->user()->id,
            ]
        );

    }
}
