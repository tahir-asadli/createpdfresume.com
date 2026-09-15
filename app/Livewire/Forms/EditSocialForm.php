<?php

namespace App\Livewire\Forms;

use App\Models\Social;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EditSocialForm extends Form
{

    #[Validate('required|max:60', 'Site')]
    public string $site = '';

    #[Validate('max:60', 'Site name')]
    public string $name = '';

    #[Validate('max:60', 'Username')]
    public string $handle = '';

    #[Validate('max:100', 'URL')]
    public string $url = '';

    #[Validate('required', 'Enabled')]
    public bool $active = true;

    public function update(Social $social)
    {
        $sites = collect(array_keys(config('site.socials')))->map(fn($a) => strtolower($a));
        $this->validate(rules: [
            'site' => "required|max:60|" . Rule::in($sites),
            'name' => 'max:60',
            'handle' => 'max:60',
            'url' => 'max:100',
            'active' => 'required',
        ], attributes: [
            'site' => __('Site'),
            'name' => __('Site name'),
            'handle' => __('Username'),
            'url' => __('URL'),
            'active' => __('Enabled'),
        ]);
        $data = [
            'site' => cl($this->site),
            'name' => cl($this->name),
            'handle' => cl($this->handle),
            'url' => cl($this->url),
            'active' => $this->active,
        ];
        $social->update($data);

    }
}
