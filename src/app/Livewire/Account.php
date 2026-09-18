<?php

namespace App\Livewire;

use App\Livewire\Forms\AccountForm;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\On;
use Livewire\Component;

class Account extends Component
{

    public AccountForm $form;

    public $executed = false;

    protected $remaining = 0;

    protected $limit = 1;


    public function getRemaining()
    {
        return $this->remaining;
    }
    public function canDownload()
    {
        return $this->remaining > 0;
    }

    public function download()
    {
        $this->remaining = RateLimiter::remaining(config('site.download_all_rate_limiter_key') . auth()->user()->id, config('site.download_all_limit'));
        $this->dispatch('refresh');
        if ($this->remaining > 0) {
            return redirect()->route('download-all');
        }
    }

    public function deleteAccount()
    {
        auth()->user()->deleteAccount();
    }

    public function hide()
    {
        $this->form->success = false;
    }

    public function submit()
    {
        $this->form->update();
    }

    public function mount()
    {
        $user = auth()->user();
        $this->form->fullname = $user->name;
        $this->form->email = $user->email;
        $this->form->language = $user->language;
        $this->remaining = RateLimiter::remaining(config('site.download_all_rate_limiter_key') . auth()->user()->id, config('site.download_all_limit'));
    }



    #[On('refresh')]
    public function render()
    {
        $this->remaining = RateLimiter::remaining(config('site.download_all_rate_limiter_key') . auth()->user()->id, config('site.download_all_limit'));
        return view('livewire.account');
    }
}
