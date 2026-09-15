<?php

namespace App\Livewire\Forms;

use App\Models\Reference;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EditReferenceForm extends Form
{

    #[Validate('required|max:60', 'Fullname')]
    public $name = '';

    #[Validate('required|max:100', 'Position')]
    public $position = '';

    #[Validate('required|max:100', 'Company')]
    public $company = '';

    #[Validate('max:60|email', 'E-mail')]
    public $email = '';

    #[Validate('max:30', 'Phone')]
    public $phone = '';

    #[Validate('required', 'Enabled')]
    public bool $active = false;

    public function update(Reference $reference)
    {
        $this->validate(attributes: [
            'name' => __('Fullname'),
            'position' => __('Position'),
            'company' => __('Company'),
            'email' => __('E-mail'),
            'phone' => __('Phone'),
            'active' => __('Enabled'),
        ]);
        $reference->update([
            'name' => cl($this->name),
            'position' => cl($this->position),
            'company' => cl($this->company),
            'email' => cl($this->email),
            'phone' => cl($this->phone),
            'active' => $this->active,
        ]);
    }
}
