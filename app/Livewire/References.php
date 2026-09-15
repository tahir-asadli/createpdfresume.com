<?php

namespace App\Livewire;

use App\Models\Reference;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Contracts\Database\Eloquent\Builder;

class References extends Component
{
    public function updateOrder(array $items)
    {
        $order = collect($items)->pluck('value')->toArray();
        Reference::setNewOrder($order, 1, 'id', function (Builder $query) {
            $query->where('user_id', auth()->user()->id);
        });
        $this->dispatch('refresh');
    }

    #[On('refresh')]
    public function render()
    {
        return view('livewire.references.index');
    }

    public function delete(Reference $reference)
    {
        Gate::authorize('delete', $reference);
        $reference->delete();
        $this->dispatch('refresh');
    }
}
