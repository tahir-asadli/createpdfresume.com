<?php

namespace App\Jobs;

use App\Mail\SendResume;
use App\Models\Resume;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;

class GenerateResumePDF implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Resume $resume, public $style = 'default')
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $result = $this->resume->getDownloadURL($this->style);
        if ($result['status'] == 'success') {
            if (App::environment('local')) {
                Mail::to('asadovtahir@gmail.com')->send(new SendResume($this->resume, $this->resume->user->language, $this->style));
            } else {
                Mail::to($this->resume->user->email)->queue(new SendResume($this->resume, $this->resume->user->language, $this->style));
            }
        }
    }
}
