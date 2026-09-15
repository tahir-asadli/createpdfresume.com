<?php

namespace App\Livewire\Forms;

use App\Models\Project;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CreateProjectForm extends Form
{
    #[Validate('required|max:60', 'Project name')]
    public $name = '';

    #[Validate('max:1000', 'Description')]
    public $description = '';

    #[Validate('date_format:d-m-Y|nullable', 'Date')]
    public string $date = '';

    #[Validate('max:100', 'URL')]
    public $url = '';

    #[Validate('required', 'Enabled')]
    public bool $active = true;


    public function save()
    {
        $this->validate(attributes: [
            'name' => __('Project name'),
            'description' => __('Description'),
            'date' => __('Date'),
            'url' => __('URL'),
            'active' => __('Enabled'),
        ]);

        Project::create(
            [
                'name' => cl($this->name),
                'description' => cl($this->description),
                'date' => $this->date ? Carbon::parse($this->date) : null,
                'url' => cl($this->url),
                'active' => $this->active,
                'user_id' => auth()->user()->id,
            ]
        );

    }
}
