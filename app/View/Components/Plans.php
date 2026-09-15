<?php

namespace App\View\Components;

use App\Models\Plan;
use App\Models\Template;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Plans extends Component
{
  /**
   * Create a new component instance.
   */
  public function __construct()
  {
    //
  }

  /**
   * Get the view / contents that represent the component.
   */
  public function render(): View|Closure|string
  {
    $plans = Plan::active()->get();
    return view('components.front.plans', compact('plans'));
  }
}
