<?php

namespace App\Livewire\Forms;

use App\Models\Language;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EditLanguageForm extends Form
{
  #[Validate('required|max:60', 'Language')]
  public string $name = '';

  #[Validate('required|integer|between:5,100', 'Percentage')]
  public int $level = 5;

  #[Validate('required', 'Enabled')]
  public bool $active = false;

  public function update(Language $language)
  {
    $this->validate(attributes: [
      'name' => __('Language'),
      'level' => __('Percentage'),
      'active' => __('Enabled'),
    ]);
    $data = [
      'name' => cl($this->name),
      'level' => cl($this->level),
      'active' => $this->active,
    ];
    $language->update($data);
  }
}
