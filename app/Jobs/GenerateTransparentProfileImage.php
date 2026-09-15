<?php

namespace App\Jobs;

use App\Mail\SendResume;
use App\Models\Profile;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Process;

class GenerateTransparentProfileImage implements ShouldQueue
{
  use Queueable;

  /**
   * Create a new job instance.
   */
  public function __construct(public Profile $profile)
  {
  }

  /**
   * Execute the job.
   */
  public function handle(): void
  {
    if (!$this->profile->profile_image) {
      return;
    }
    $original_image = $this->profile->profile_image;
    $original_image_path = storage_path("app/public/{$original_image}");
    $original_image_filename = pathinfo($original_image, PATHINFO_FILENAME);
    $transparent_image_name = "{$original_image_filename}_transparent.png";
    $transparent_image_path = storage_path("app/public/photos/{$transparent_image_name}");
    $db_transparent_image_path = "photos/{$transparent_image_name}";
    $processPath = '/home/tahir/.nvm/versions/node/v24.15.0/bin/node --max-old-space-size=512 /home/tahir/Documents/GitHub/tahir-asadli/Image-Background-Remover/index.js';
    if (App::environment('production')) {
      $processPath = '/usr/bin/node --max-old-space-size=512 /home/server/Apps/bgremover/index.js';
    }
    if (is_file($original_image_path)) {
      $commandResult = Process::run("$processPath $original_image_path $transparent_image_path");
      if ($commandResult->successful() && is_file($transparent_image_path)) {
        $this->profile->transparent_profile_image = $db_transparent_image_path;
        $this->profile->transparent = true;
        $this->profile->save();
      } else {
        info('error transparent');
      }
    }
    // $result = $this->resume->getDownloadURL($this->style);
    // if ($result['status'] == 'success') {
    //   if ($this->resume->user->isAdmin()) {
    //     Mail::to($this->resume->user->email)->send(new SendResume($this->resume, $this->resume->user->language, $this->style));
    //   } else {
    //     Mail::to($this->resume->user->email)->queue(new SendResume($this->resume, $this->resume->user->language, $this->style));
    //   }
    // }
  }
}
