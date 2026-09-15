<?php

namespace App\Livewire;

use App\Models\Experience;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Contracts\Database\Eloquent\Builder;

class Experiences extends Component
{
  public function updateOrder(array $items)
  {
    $order = collect($items)->pluck('value')->toArray();
    Experience::setNewOrder($order, 1, 'id', function (Builder $query) {
      $query->where('user_id', auth()->user()->id);
    });
    $this->dispatch('refresh');
  }

  #[On('refresh')]
  public function render()
  {
    return view('livewire.experiences.index');
  }

  public function delete(Experience $experience)
  {
    Gate::authorize('delete', $experience);
    $experience->delete();
    $this->dispatch('refresh');
  }
}
