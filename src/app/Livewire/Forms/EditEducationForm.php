<?php

namespace App\Livewire\Forms;

use App\Models\Education;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EditEducationForm extends Form
{
    #[Validate('required|max:120', 'Place of education')]
    public string $school = '';

    #[Validate('required|max:120', 'Degree')]
    public string $degree = '';

    #[Validate('max:120', 'Field')]
    public string $field = '';

    #[Validate('max:120', 'Address')]
    public string $location = '';

    #[Validate('max:1000', 'Description')]
    public string $description = '';

    #[Validate('date_format:d-m-Y|nullable', 'Start date')]
    public string $startDate = '';

    #[Validate('date_format:d-m-Y|nullable', 'End date')]
    public string $endDate = '';

    #[Validate('required', 'Enabled')]
    public bool $active = true;

    public function update(Education $education)
    {
        $this->validate(attributes: [
            'school' => __('Place of education'),
            'degree' => __('Degree'),
            'field' => __('Field'),
            'location' => __('Address'),
            'description' => __('Description'),
            'startDate' => __('Start date'),
            'endDate' => __('Start date'),
            'active' => __('Enabled'),
        ]);
        $education->update([
            'school' => cl($this->school),
            'degree' => cl($this->degree),
            'field' => cl($this->field),
            'location' => cl($this->location),
            'description' => cl($this->description),
            'start_date' => $this->startDate ? Carbon::parse($this->startDate) : null,
            'end_date' => $this->endDate ? Carbon::parse($this->endDate) : null,
            'active' => $this->active,
        ]);
    }
}
