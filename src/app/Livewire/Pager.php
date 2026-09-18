<?php

namespace App\Livewire;

use App\Models\Block;
use App\Models\Hobby;
use App\Models\Resume;
use App\Models\Section;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;

class Pager extends Component
{


  public Resume $resume;
  public $page = 1;
  public $router = 'builder';

  public function next()
  {

    $pageLimit = auth()->user()->PDFPageLimit();
    if ($this->page < $pageLimit) {
      $this->page++;
      return redirect(route($this->router, [$this->resume->uuid, $this->page]));
    }
  }
  public function prev()
  {
    if ($this->page > 1) {
      $this->page--;
      return redirect(route($this->router, [$this->resume->uuid, $this->page]));
    }
  }
  #[On('refresh')]
  public function render()
  {
    return view('livewire.pager');
  }
}
