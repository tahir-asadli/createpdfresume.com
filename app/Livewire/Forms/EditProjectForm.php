<?php

namespace App\Livewire\Forms;

use App\Models\Project;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EditProjectForm extends Form
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
    public bool $active = false;

    public function update(Project $project)
    {
        $this->validate(attributes: [
            'name' => __('Project name'),
            'description' => __('Description'),
            'date' => __('Date'),
            'url' => __('URL'),
            'active' => __('Enabled'),
        ]);
        $project->update([
            'name' => cl($this->name),
            'description' => cl($this->description),
            'url' => cl($this->url),
            'date' => $this->date ? Carbon::parse($this->date) : null,
            'active' => $this->active,
        ]);
    }
}
