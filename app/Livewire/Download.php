<?php

namespace App\Livewire;

use App\Jobs\GenerateResumePDF;
use App\Models\Resume;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;
use Livewire\Attributes\On;

class Download extends Component
{

    public Resume $resume;
    public $text = 'Generate PDF';
    public $download_error = '';
    public $download_success = '';

    public $disabled = false;

    public $limit = 0;
    public $remaining = 0;

    public $forMobile = false;
    public $isButton = true;

    public function mount()
    {
        $this->text = __('Generate PDF');
        $this->limit = auth()->user()->PDFGenerationLimit();
        $this->remaining = RateLimiter::remaining('generate' . auth()->user()->id, $this->limit);
    }

    public function reload()
    {
        $this->disabled = false;
        $this->text = __('Generate PDF');
    }

    public function download()
    {
        $this->disabled = true;
        $this->text = __('Sending email...');
        $executed = RateLimiter::attempt(
            'generate' . auth()->user()->id,
            // 'generate' . $this->resume->id . auth()->user()->id,
            auth()->user()->PDFGenerationLimit(),
            function () {
                GenerateResumePDF::dispatch($this->resume, $this->resume->style);
                // $result = $this->resume->getDownloadURL();
                // if ($result['status'] == 'success') {
                //     return redirect($result['download_url']);
                // }
                // $this->download_error = $result['message'];
            },
            86400
        );

        $this->remaining = RateLimiter::remaining('generate' . auth()->user()->id, auth()->user()->PDFGenerationLimit());
        if (!$executed) {
            $this->download_error = __('Too many messages sent!');
        }
        $this->dispatch('refresh');
    }

    #[On('refresh')]
    public function render()
    {
        return view('livewire.download');
    }
}
