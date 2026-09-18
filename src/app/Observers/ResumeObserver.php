<?php

namespace App\Observers;

use Illuminate\Support\Str;
use App\Models\Resume;

class ResumeObserver
{
  /**
   * Handle the Resume "creating" event.
   */
  public function creating(Resume $resume): void
  {
    $resume->uuid = Str::uuid()->toString();
  }

  /**
   * Handle the Resume "updated" event.
   */
  public function updated(Resume $resume): void
  {
    //
  }

  /**
   * Handle the Resume "deleted" event.
   */
  public function deleted(Resume $resume): void
  {
    //
  }

  /**
   * Handle the Resume "restored" event.
   */
  public function restored(Resume $resume): void
  {
    //
  }

  /**
   * Handle the Resume "force deleted" event.
   */
  public function forceDeleted(Resume $resume): void
  {
    //
  }
}
