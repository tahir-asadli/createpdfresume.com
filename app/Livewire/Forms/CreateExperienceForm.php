<?php

namespace App\Livewire\Forms;

use App\Models\Experience;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CreateExperienceForm extends Form
{
    #[Validate('required|max:120', 'Company')]
    public string $company = '';

    #[Validate('required|max:120', 'Position')]
    public string $position = '';

    #[Validate('max:1000', 'About company')]
    public string $about = '';

    #[Validate('max:120', 'Address')]
    public string $location = '';

    #[Validate('date_format:d-m-Y|nullable', 'Start date')]
    public string $startDate = '';

    #[Validate('date_format:d-m-Y|nullable', 'Start date')]
    public string $endDate = '';

    #[Validate('required', 'Enabled')]
    public bool $active = true;

    public function save()
    {
        $this->validate(attributes: [
            'company' => __('Company'),
            'position' => __('Position'),
            'about' => __('About company'),
            'location' => __('Address'),
            'startDate' => __('Start date'),
            'endDate' => __('Start date'),
            'active' => __('Enabled'),
        ]);
        Experience::create(
            [
                'company' => cl($this->company),
                'position' => cl($this->position),
                'about' => cl($this->about),
                'location' => cl($this->location),
                'start_date' => $this->startDate ? Carbon::parse($this->startDate) : null,
                'end_date' => $this->endDate ? Carbon::parse($this->endDate) : null,
                'active' => $this->active,
            ] + ['user_id' => auth()->user()->id]
        );
    }
}
