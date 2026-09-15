<?php

namespace App\Livewire\Forms;

use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AccountForm extends Form
{

    public $success = false;

    #[Validate('required|max:100', 'Fullname')]
    public $fullname = '';

    #[Validate('nullable|min:6|max:64', 'Password')]
    public $password = '';

    #[Validate('required|in:en,az,tr,ru,es', 'Language')]
    public $language = 'en';

    public $email = '';

    public function update()
    {
        $this->validate(attributes: [
            'fullname' => __('Fullname'),
            'password' => __('Password'),
            'email' => __('E-mail'),
            'language' => __('Language'),
        ]);
        $data = [
            'name' => cl($this->fullname),
            'language' => cl($this->language),
        ];
        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }
        if (auth()->user()->update($data)) {
            app()->setLocale($this->language);
            $this->success = true;
        }
    }
}
