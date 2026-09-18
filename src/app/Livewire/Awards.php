<?php

namespace App\Livewire;

use App\Models\Award;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Contracts\Database\Eloquent\Builder;

class Awards extends Component
{
    public function updateOrder(array $items)
    {
        $order = collect($items)->pluck('value')->toArray();
        Award::setNewOrder($order, 1, 'id', function (Builder $query) {
            $query->where('user_id', auth()->user()->id);
        });
        $this->dispatch('refresh');
    }

    #[On('refresh')]
    public function render()
    {
        return view('livewire.awards.index');
    }

    public function delete(Award $award)
    {
        Gate::authorize('delete', $award);
        $award->delete();
        $this->dispatch('refresh');
    }
}
