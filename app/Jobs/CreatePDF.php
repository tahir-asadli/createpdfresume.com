<?php

namespace App\Jobs;

use App\Mail\SendResume;
use App\Models\Resume;
use App\Models\Tracking;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;

class CreatePDF implements ShouldQueue
{
  use Queueable;

  /**
   * Create a new job instance.
   */
  public function __construct(public Resume $resume, public $style = 'default', public $trackingId = null)
  {
  }

  /**
   * Execute the job.
   */
  public function handle(): void
  {

    if (!$this->trackingId) {
      return;
    }
    $tracking = Tracking::where('uuid', $this->trackingId)->first();
    if (!$tracking) {
      return;
    }
    if ($tracking) {
      $generated = $this->resume->generatePDF($this->style);
      if ($generated) {
        $data = ['download_url' => route('download', [$this->resume, $this->style])];
        if (App::environment('local')) {
          Mail::to('asadovtahir@gmail.com')->queue(new SendResume($this->resume, $this->resume->user->language, $this->style));
        } else {
          Mail::to($this->resume->user->email)->queue(new SendResume($this->resume, $this->resume->user->language, $this->style));
        }
        $tracking->status = 'completed';
        $tracking->data = serialize($data);
        $tracking->save();
      } else {
      }
    }
  }
}
