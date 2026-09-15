<?php

namespace App\Livewire\Forms;

use App\Models\Award;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CreateAwardForm extends Form
{
    #[Validate('required|max:100', 'Award name')]
    public $name = '';

    #[Validate('required|max:500', 'Organization')]
    public $organization = '';

    #[Validate('max:1000', 'Description')]
    public $description = '';

    #[Validate('date_format:d-m-Y|nullable', 'Date')]
    public string $date = '';

    #[Validate('required', 'Enabled')]
    public bool $active = true;

    public function save()
    {
        $this->validate(attributes: [
            'name' => __('Award name'),
            'organization' => __('Organization'),
            'description' => __('Description'),
            'date' => __('Date'),
            'active' => __('Enabled'),
        ]);

        Award::create(
            [
                'name' => cl($this->name),
                'organization' => cl($this->organization),
                'date' => $this->date ? Carbon::parse($this->date) : null,
                'description' => cl($this->description),
                'active' => $this->active,
                'user_id' => auth()->user()->id,
            ]
        );

    }
}
