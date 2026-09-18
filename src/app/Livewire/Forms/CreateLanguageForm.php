<?php

namespace App\Livewire\Forms;

use App\Models\Language;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CreateLanguageForm extends Form
{
  #[Validate('required|max:60', 'Language')]
  public string $name = '';

  #[Validate('required|integer|between:5,100', 'Percentage')]
  public int $level = 100;

  #[Validate('required', 'Enabled')]
  public bool $active = true;


  public function save()
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
    Language::create(
      $data + ['user_id' => auth()->user()->id]
    );

  }
}
