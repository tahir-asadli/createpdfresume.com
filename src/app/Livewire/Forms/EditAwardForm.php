<?php

namespace App\Livewire\Forms;

use App\Models\Award;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EditAwardForm extends Form
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
    public bool $active = false;

    public function update(Award $award)
    {
        $this->validate(attributes: [
            'name' => __('Award name'),
            'organization' => __('Organization'),
            'description' => __('Description'),
            'date' => __('Date'),
            'active' => __('Enabled'),
        ]);
        $award->update([
            'name' => cl($this->name),
            'description' => cl($this->description),
            'organization' => cl($this->organization),
            'date' => $this->date ? Carbon::parse($this->date) : null,
            'active' => $this->active,
        ]);
    }
}
