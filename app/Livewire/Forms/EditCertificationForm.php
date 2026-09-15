<?php

namespace App\Livewire\Forms;

use App\Models\Certification;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EditCertificationForm extends Form
{
    #[Validate('required|max:100', 'Certificate name')]
    public $name = '';

    #[Validate('required|max:100', 'Organization')]
    public $organization = '';

    #[Validate('max:1000', 'Description')]
    public $description = '';

    #[Validate('date_format:d-m-Y|nullable', 'Date')]
    public string $date = '';

    #[Validate('required', 'Enabled')]
    public bool $active = false;

    public function update(Certification $certification)
    {
        $this->validate(attributes: [
            'name' => __('Certificate name'),
            'organization' => __('Organization'),
            'description' => __('Description'),
            'date' => __('Date'),
            'active' => __('Enabled'),
        ]);
        $certification->update([
            'name' => cl($this->name),
            'description' => cl($this->description),
            'organization' => cl($this->organization),
            'date' => $this->date ? Carbon::parse($this->date) : null,
            'active' => $this->active,
        ]);
    }
}
