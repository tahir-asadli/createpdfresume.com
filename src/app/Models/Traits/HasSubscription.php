<?php


namespace App\Models\Traits;

use App\Models\Block;
use App\Models\Resume;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Rct567\DomQuery\DomQuery;

trait HasSubscription
{

  public function hasSubscription()
  {
    return $this->subscription()->active()->where('ends_at', '>', Carbon::now())->first();
  }

  public function cancelSubscription()
  {
    if ($this->subscription()->active()->where('ends_at', '>', Carbon::now())->first()) {
      return $this->subscription()->update([
        'ends_at' => null,
        'status' => 'cancelled',
      ]);
    }
    return true;
  }

  public function plan()
  {
    if ($this->subscription()->active()->where('ends_at', '>', Carbon::now())->first()) {
      return $this->subscription()->active()->where('ends_at', '>', Carbon::now())->first()->plan;
    }
    return null;
  }

  public function planSlug()
  {
    if ($this->subscription()->active()->where('ends_at', '>', Carbon::now())->first()) {
      return $this->subscription()->active()->where('ends_at', '>', Carbon::now())->first()->plan->slug;
    } else {
      return 'free';
    }
  }

  public function hasFreePlan()
  {
    return $this->planSlug() == 'free';
  }


  public function shouldUpgrade()
  {
    return $this->planSlug() == 'free' || $this->planSlug() == null;
  }

  public function canAccess($requiredPlan)
  {
    if ($requiredPlan == 'free') {
      return true;
    }
    if ($userPlan = $this->plan()) {
      if ($requiredPlan == 'standart') {
        if ($userPlan->slug == 'premium' || $userPlan->slug == 'standart') {
          return true;
        }
      } else if ($requiredPlan == 'premium') {
        if ($userPlan->slug == 'premium') {
          return true;
        }
      }
    }
    return false;
  }

  public function PDFGenerationLimit()
  {
    if (!App::environment('production')) {
      return 123;
    }

    if ($this->isAdmin()) {
      return 999;
    }

    // $subscription = $this->hasSubscription();
    // if ($subscription) {
    //   return $subscription->plan->generation_count;
    // }

    return setting('freePlanPdfLimit', config('site.freePlanPdfLimit'));

  }

  public function PDFPageLimit()
  {
    if (!App::environment('production')) {
      return 123;
    }

    if ($this->isAdmin()) {
      return 90;
    }

    // $subscription = $this->hasSubscription();
    // if ($subscription) {
    //   return $subscription->plan->page_count;
    // }

    // 
// 

    return setting('freePlanPageLimit', config('site.freePlanPageLimit'));

  }

  public function PDFResumeLimit()
  {
    if (!App::environment('production')) {
      return 123;
    }

    // $subscription = $this->hasSubscription();
    // if ($subscription) {
    //   return $subscription->plan->resume_count;
    // }

    if ($this->isAdmin()) {
      return 999;
    }

    return setting('freePlanCVLimit', config('site.freePlanCVLimit'));

  }

}